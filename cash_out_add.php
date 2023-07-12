<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$last_id = get_id_new_order($connect);


if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {

        $cash_date = $_POST['cash_date'];
        $state_id = $_POST['state_id'];
        $cash_type = $_POST['cash_type'];
        $cash_sum = str_replace(' ', '', $_POST['cash_sum']);
        $cash_comment = $_POST['cash_comment'];


        cash_out_add($connect, $state_id,  $cash_sum, $cash_type, $cash_comment, $cash_date);
    
}

//get counterparties
$sql = "SELECT * FROM state_out";
$state_tbl = mysqli_query ($connect, $sql);


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <title>ortosavdo</title>
</head>
<body>
    <!-- Container element to hold the snipping GIF -->
<div id="snipping-container"></div>
    
<?php include 'partSite/nav.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Добавления расходника</span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <input data-toggle="modal" data-target="#exampleModalAll" class="btn btn-success" type="submit" value="Сохранить" />
        <a href="cash_out.php"><button type="button" class="btn btn-custom">Закрыть</button></a>
    </div>
</div>

<section class="card_head">
    <div class="container-fluid">
        <form action="" method="POST" class="horizntal-form" id="input_form">

            
            <div class="row">
                <div class="col-md-3">
                        <span>Дата</span>
                </div>
            </div>
            <div class="row">
               
            <div class="col-md-3">
                    <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="cash_date">
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Вид движения</span>
                </div>
                <div class="col-md-3">
                    <span>Тип оплаты</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"> 
                <select required class="normalize" name="state_id">
                        <option value=""></option>
                        <?php    
                            while ($option_state = mysqli_fetch_array($state_tbl)) {    
                        ?>
                            <option value="<?php echo $option_state["id"];?>"><?php echo $option_state["name"]?></option>
                        <?php
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select required name="cash_type" class="normalize"">
                        <option value=""></option>
                        <option value="Перечисление">Перечисление</option>
                        <option value="Наличные деньги">Наличные деньги</option>
                    </select>
                </div>
            </div>
            <div class="row mt">
            <div class="col-md-3">
                    <span>Сумма</span>
                </div>
                <div class="col-md-3">
                    <span>Примечание</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input required  class="form-control autonumeric" name="cash_sum">
                </div>  
                <div class="col-md-3">
                    <textarea name="cash_comment" type="text" class="form-control" value="" rows="1" placeholder="">Расход с кассы</textarea>
                </div>
            </div>
        </form>
    </div>
</section>


<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.js" integrity="sha512-/lbeISSLChIunUcgNvSFJSC+LFCZg08JHFhvDfDWDlY3a/NYb/NPKOcfDte3aA6E3mxm9a3sdxvkktZJSCpxGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="js/snipping.js"></script>
</body>
</html>

<script type ="text/javascript">
    

    
$('.normalize').selectize();



//AutoMuneric
const autoNumericOptionsEuro = {
    decimalPlaces: '0',
    digitGroupSeparator        : ' ',
    decimalCharacter           : ',',
    decimalCharacterAlternative: '.'
};

// Initialization
new  AutoNumeric.multiple('.autonumeric', autoNumericOptionsEuro);


//End AutoMuneric



</script>

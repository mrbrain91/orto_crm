<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$last_id = get_id_new_order($connect);


if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {

        $id_counterpartie = $_POST['id_counterpartie'];
        $prepayment_date = $_POST['prepayment_date'];
        echo $prepayment_sum = str_replace(' ', '', $_POST['prepayment_sum']);
        $payment_type = $_POST['payment_type'];
    
        add_debt_supplier($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type);
}

//get supplier
$sql = "SELECT * FROM supplier_tbl";
$supplier_tbl = mysqli_query ($connect, $sql);


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
        <span class="right_cus">Добавление оплата</span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <!-- <button type="button" class="btn btn-primary">Сохранить</button> -->
        <!-- <button type="submit" form="order_form" name="save_add_pro" class="btn btn-success">Принять</button> -->
        <input data-toggle="modal" data-target="#exampleModalAll" class="btn btn-success" type="submit" value="Сохранить" />
        <a href="supplier_list.php"><button type="button" class="btn btn-custom">Закрыть</button></a>

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
                    <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="prepayment_date">
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Доставщик</span>
                </div>
                <div class="col-md-3">
                    <span>Баланс доставщика</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"> 
                <select required class="normalize" name="id_counterpartie" onchange="showCustomer(this.value)">
                        <option value=""></option>
                        <?php    
                            while ($option_contractor = mysqli_fetch_array($supplier_tbl)) {    
                        ?>
                            <option value="<?php echo $option_contractor["id"];?>"><?php echo $option_contractor["name"]?></option>
                        <?php
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <div id="balance">
                        <input disabled type="text" class="form-control" value="">
                    </div>
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Тип оплаты</span>
                </div>
                <div class="col-md-3">
                    <span>Сумма</span>
                </div>
            </div>
            <div class="row">
            <div class="col-md-3">
                    <select required name="payment_type" class="normalize"">
                        <option value=""></option>
                        <option value="Перечисление">Перечисление</option>
                        <option value="Наличные деньги">Наличные деньги</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input required  class="form-control autonumeric" name="prepayment_sum">
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

// -------------------------------------------- select bazadan olish-------------------------------------------------------

function showCustomer(str) {
    // console.log(str);
  var xhttp;    
  if (str == "") {
    document.getElementById("balance").innerHTML = "";
    // document.getElementById("sample").innerHTML = 'hi';
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("balance").innerHTML = this.responseText;

    }
  };
  xhttp.open("GET", "getcustomer.php?id_s="+str+"", true);
  xhttp.send();
}


</script>

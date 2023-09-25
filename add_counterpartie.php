<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

//get  users
$sql = "SELECT * FROM users_tbl WHERE role='sale'";
$users_tbl = mysqli_query ($connect, $sql);
//end get users 

$last_id = get_id_new_order($connect);


if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
    $name = $_POST['name'];	
    $user_id = $_POST['user_id'];	
    $keys = array(
        'alternative_name',
        'inn',
        'nds',
        'raschetny_schet',
        'mfo',
        'address',
        'contact',
        'director',
        'accountant'
    );
    
    foreach ($keys as $key) {
        if (!empty($_POST[$key])) {
            $$key = $_POST[$key];
        } else {
            $$key = "-";
        }
    }
    add_counterparties($connect, $name, $alternative_name, $inn, $nds, $raschetny_schet, $mfo, $address, $contact, $director, $accountant, $user_id);
}


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
        <span class="right_cus">Добавление контагента</span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <td><input data-toggle="modal" data-target="#exampleModal3" class="btn btn-success" type="submit" value="Сохранить" />

        <a href="counterparties.php"><button type="button" class="btn btn-custom">Закрыть</button></a>

    </div>
</div>

<section class="card_head">
    <div class="container-fluid">
        <form action="" method="POST" class="horizntal-form" id="counterpartie_form">
            <div class="row">
                <div class="col-md-3">
                    <span>Название <span style="color:red;">*</span></span>
                </div>
                <div class="col-md-3">
                    <span>Альтернативное название</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input  required type="text" class="form-control" name="name" form="counterpartie_form" >
                </div>
                <div class="col-md-3"> 
                    <input type="text" class="form-control" name="alternative_name" form="counterpartie_form">
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Прикрепить торговый представитель <span style="color:red;">*</span></span>
                </div>
                <div class="col-md-3">
                    <span>ИНН/ПНФЛ</span>
                </div>
                <div class="col-md-3">
                    <span>Регистрационный код плательщика НДС</span>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-3">
                    <select required name="user_id" form="counterpartie_form" class="normalize">
                        <option value="">--выберитe---</option>
                        <?php    
                            while ($option = mysqli_fetch_array($users_tbl)) {    
                        ?>
                            <option value="<?php echo $option["id"];?>"><?php echo $option["surname"]?> <?php echo $option["name"];?> <?php echo $option["fathername"];?></option>
                        <?php       
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="inn" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="nds" form="counterpartie_form">
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Адрес</span>
                </div>
                <div class="col-md-3">
                    <span>Расчетный счет</span>
                </div>
                <div class="col-md-3">
                    <span>МФО</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="address" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="raschetny_schet" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="mfo" form="counterpartie_form">
                </div>
            </div>
            <div class="row mt">
            <div class="col-md-3">
                    <span>Контакт</span>
                </div>
                <div class="col-md-3">
                    <span>Директор</span>
                </div>
                <div class="col-md-3">
                    <span>Гл.Бухгалтер</span>
                </div>
            </div>
            <div class="row">
            <div class="col-md-3">
                    <input  type="text" class="form-control" name="contact" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="director" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input  type="text" class="form-control" name="accountant" form="counterpartie_form">
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
<script src="js/snipping.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>


<script>
    $('.normalize').selectize();
</script>
</body>
</html>


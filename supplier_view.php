<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM supplier_tbl WHERE id='$id'";  
    $rs_result = mysqli_query ($connect, $query);  
    $res = mysqli_fetch_assoc($rs_result);
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
    <link rel="stylesheet" href="css/style.css">
    <title>ortosavdo</title>
</head>
<body>
    
<?php include 'partSite/nav.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Просмотр доставщик №<?php echo $id; ?></span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <a href="supplier.php"><button type="button" class="btn btn-custom">Закрыть</button></a>
    </div>
</div>

<section class="card_head">
    <div class="container-fluid">
        <form action="" method="POST" class="horizntal-form" id="counterpartie_form">
            <div class="row">
                <div class="col-md-3">
                    <span>Название</span>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input disabled required type="text" class="form-control" name="name" value='<?php  echo $res['name'];?>' form="counterpartie_form" >
                </div>
                
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Альтернативное название</span>
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
                    <input required type="text" class="form-control" name="alternative_name" disabled value='<?php  echo $res['alternative_name'];?>' form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="inn" disabled value="<?php  echo $res['inn'];?>" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="nds" disabled value="<?php  echo $res['nds'];?>" form="counterpartie_form">
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
                    <input required type="text" class="form-control" name="address" disabled value="<?php  echo $res['address'];?>" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="raschetny_schet" disabled value="<?php  echo $res['raschetny_schet'];?>" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="mfo" disabled value="<?php  echo $res['mfo'];?>" form="counterpartie_form">
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
                    <input required type="text" class="form-control" name="contact" disabled value="<?php  echo $res['contact'];?>" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="director" disabled value="<?php  echo $res['director'];?>" form="counterpartie_form">
                </div>
                <div class="col-md-3">
                    <input required type="text" class="form-control" name="accountant" disabled value="<?php  echo $res['accountant'];?>" form="counterpartie_form">
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
</body>
</html>


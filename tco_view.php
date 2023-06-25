<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$last_id = get_id_new_order($connect);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM state_out WHERE id='$id'";  
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
        <span class="right_cus">Просмотр статьи №<?php echo $id; ?></span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <!-- <button type="button" class="btn btn-primary">Сохранить</button>
        <!-- <button type="submit" form="order_form" name="save_add_pro" class="btn btn-success">Принять</button> -->
        <!-- <td><input class="btn btn-success" type="submit" form="state_in_form" name="submit" value="Сохранить" /> -->

        <a href="type_cash_out.php"><button type="button" class="btn btn-custom">Закрыть</button></a>

    </div>
</div>

<section class="card_head">
    <div class="container-fluid">
        <form action="" method="POST" class="horizntal-form" id="state_in_form">

            <div class="row ">
                <div class="col-md-3">
                    <span>Название</span>
                </div> 
            </div>
            <div class="row">
                <div class="col-md-3"> 
                    <input disabled value='<?php  echo $res['name'];?>' type="text" class="form-control" name="name" form="state_in_form">
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


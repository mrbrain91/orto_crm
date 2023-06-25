<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $payment_type = $_GET['payment_type'];
    $sale_agent = $_GET['sale_agent'];
    $contractor = $_GET['contractor'];
    $ord_date = $_GET['date'];

    $ord_deliver_date = $_GET['del_date'];

    $sum = get_sum_id_main($connect, $id)."<br>";
    $sum_count = sum_count_main($connect, $id)."<br>";
    
}


$query = "SELECT * FROM main_ord__item_tbl WHERE order_id='$id'";  
$rs_result = mysqli_query ($connect, $query);


?>


<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
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
        <span class="right_cus">Просмотр заказ №<?php echo $id; ?></span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <a href="order.php"><button type="button" class="btn btn-custom">Закрыть</button></a>
            <button class="btn btn-custom " onClick="window.print()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> печать</button>
        </div>
</div>


<div id="section-to-print">
    <!-- start card head information -->
    <div class="container-fluid">
        <div class="card_head">
            <div class="card_head__wrapper">
                <div class="row">
                    <div class="col-sm-4">
                    Контрагент
                    </div>
                    <div class="col-sm-8">
                    <?php $contractor = get_contractor($connect, $contractor);?><?php echo $contractor["name"]; ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                    Дата сделки
                    </div>
                    <div class="col-sm-8">
                        <?php echo $ord_date = date("d.m.Y", strtotime($ord_date)); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Дата отгрузки
                    </div>
                    <div class="col-sm-8">
                        <?php echo $ord_date = date("d.m.Y", strtotime($ord_deliver_date)); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Условия оплаты
                    </div>
                    <div class="col-sm-8">
                    <?php echo $payment_type; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Торговый представитель
                    </div>
                    <div class="col-sm-8">
                    <?php $user = get_user($connect, $sale_agent);?><?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End card head information -->

    <!-- Start prod list -->
    <div class="prod_list">
        <div class="container-fluid">
            <table class="table table-hover">
                <thead>
                    <tr class="w600">
                        <td>№</td>
                        <td>Наименование товаров</td>
                        <td>Количество</td>
                        <td>Ед. изм.</td>
                        <td>Цена</td>
                        <td>Скидка</td>
                        <td>Сумма </td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $n = 0;    
                    while ($row = mysqli_fetch_array($rs_result)) {  
                    $n++; 
                    if ($row["sale_type"] === 'percent') {
                        $sale_type = '%';
                    }elseif($row["sale_type"] === 'sum') {
                        $sale_type = 'сум';
                    }
                    

                    $name = get_prod_name($connect, $row["prod_name"]);

                    $query = "SELECT * FROM products_tbl WHERE name='$name[name]'";  
                    $unit_result = mysqli_query ($connect, $query);
                        if(!$unit_result)
                        die(mysqli_error($connect));
                    $unit_name = mysqli_fetch_assoc($unit_result);
                    $unit_name =  $unit_name['unit'];
                ?> 
                    <tr>
                        <td class="col-sm-1">
                            <span><?php echo $n; ?></span>
                        </td>
                        <td class="col-sm-4" >
                            <span><?php echo $name['name']; ?></span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo number_format($row['count_name'], 0, ',', ' '); ?></span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo $unit_name; ?></span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo number_format($row['price_name'], 0, ',', ' '); ?></span>
                            
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo $row["sale_name"]; ?> <?php echo $sale_type; ?></span>
                        </td>
                        <td class="col-sm-2">
                            <span><?php echo number_format($row['total_name'], 0, ',', ' '); ?></span>

                        </td>

                    </tr>
                    
                    <?php     
                        };    
                    ?>
                    <tr>
                        <td class="w600"><span style="float:left;">Итого к оплате</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="w600"><?php echo number_format($sum, 0, ',', ' '); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end prod list -->
    
   
</div>



<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>


</body>
</html>
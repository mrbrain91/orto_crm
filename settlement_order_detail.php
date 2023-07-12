<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}



// $query = "SELECT * FROM settlements WHERE status='0' AND debt>'0' ORDER BY id DESC";
// $rs_result = mysqli_query ($connect, $query);

if (isset($_GET["order_id"])) {
    $order_id = $_GET["order_id"];
    $id = $_GET["id"];
    $prepayment = $_GET["prepayment"];
}

$query = "SELECT order_date, debt, prepayment FROM debts WHERE order_id = '$order_id'";
$rs_result = mysqli_query ($connect, $query);

// $row1 = mysqli_fetch_assoc(mysqli_query($connect, $query1));
// $total_prepayment = $row1['total_prepayment'];


?>


<!DOCTYPE html>
<html lang="ru">
<head>
  
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
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
        <span class="right_cus">История взаимарасчетов</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
           <!-- <a href="#"> <button type="button" class="btn btn-success">Взаимозачет</button> </a> -->
           <!-- <a href="add_order.php"> <button type="button" class="btn btn-primary">должники</button> </a> -->
           <a href="settlement_debts_detail.php?id=<?php echo $id;?>&&prepayment=<?php echo $prepayment?>"> <button type="button" class="btn btn-custom">закрыть</button> </a>
        </div>
</div>

<div class="all_table">
    <div class="container-fluid">
        
        <table class="table table-hover" style="width:50%; margin: 0 auto; margin-top: 30px;">
        <thead>
            <caption><span class="ordernum">Номер заказа: <?php echo $order_id?></span></caption>
            <tr>
                <th scope="col">Дата</th>
                <th scope="col">Основной долг</th>
                <th scope="col">Оплачено </th>
                <th scope="col">Разница</th>
            </tr>
        </thead>
        <tbody>


            <?php     
                $i = 0;
                $sum_debt = 0;
                $sum_prepayment = 0;
                while ($row = mysqli_fetch_array($rs_result)) {
                $i++;
                $sum_debt += $row["debt"];
                $sum_prepayment += $row["prepayment"];
                $last_debt = $sum_debt - $sum_prepayment;
            ?> 

            <tr data-toggle="collapse" data-target="#hidden_<?php echo $i;?>">
                <!-- <td><?php echo $row["order_date"]; ?></td> -->
                <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>

                <td><?php echo number_format($row["debt"], 0, ',', ' '); ?></td>
                <td><?php echo number_format($row["prepayment"], 0, ',', ' '); ?></td>
            </tr>

            <?php       
                };     
            ?>
            <tr>
                <td class="ordernum">Итого:</td>
                <td class="ordernum"><?php echo number_format($sum_debt, 0, ',', ' '); ?></td>
                <td class="ordernum"><?php echo number_format($sum_prepayment, 0, ',', ' '); ?></td>
                <td class="ordernum"><?php echo number_format($last_debt, 0, ',', ' '); ?></td>
            </tr>


        </tbody>
        </table>
    </div>
</div>




<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>
<script src="js/snipping.js"></script>
</body>
</html>
<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}



$query = "SELECT * FROM main_ord_tbl WHERE order_status='1' ORDER by id DESC";  
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
        <span class="right_cus">Архив заказов</span>
    </div>    
</div>
<!-- 
<div class="toolbar">
        <div class="container-fluid">
           <a href="add_order.php"> <button type="button" class="btn btn-success">Добавить</button> </a>
        </div>
</div> -->

<div class="all_table">
    <div class="container-fluid">
        <table class="table table-hover">
        <thead>
            <tr>
            <!-- <th scope="col">Номер заказа</th> -->
            <th scope="col">Контрагент</th>
            <th scope="col">Торговый представитель</th>
            <th scope="col">Дата заказа</th>
            <th scope="col">Тип оплаты</th>
            <th scope="col">Сумма сделки</th>
            <th scope="col">Просмотр</th>
            <th scope="col">Восстановить</th>
            

            </tr>
        </thead>
        <tbody>
<?php     
    while ($row = mysqli_fetch_array($rs_result)) {    
?> 
            <tr>
            
                <!-- <td><?php echo $row["id"]; ?></td> -->
                <td><?php echo $row["contractor"]; ?></td>
                <td><?php echo $row["sale_agent"]; ?></td>
                <td><?php echo $row["ord_date"]; ?></td>
                <td><?php echo $row["payment_type"]; ?></td>
                <td><?php echo number_format($row['transaction_amount']); ?></td>
                <td><a href="">Просмотр</a></td>
                <td><a href="" onclick="return confirm('Восстановить?')" role="button">Восстановить</a></td>
            </tr>
<?php       
    };    
?>
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
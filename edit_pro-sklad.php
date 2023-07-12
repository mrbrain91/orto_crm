<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $date = $_GET['date'];
    $note = $_GET['note'];
    $sum = get_sum_id($connect, $id);
    $sum_count = sum_count($connect, $id);
    
    upd_order_sum($connect, $id, $sum);
    
}


$query = "SELECT * FROM order_item_product WHERE order_id='$id'";  
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
<!-- Container element to hold the snipping GIF -->
<div id="snipping-container"></div>

<?php include 'partSite/nav-sklad.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Редактировать прихода №<?php echo $id; ?></span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <a href="in_store-sklad.php"><button type="button" class="btn btn-custom">Закрыть</button></a>

        </div>
</div>
<!-- start card head information -->
<div class="container-fluid">
    <div class="card_head">
        <div class="card_head__wrapper">
            <div class="row">
                <div class="col-sm-4">
                    Номер прихода
                </div>
                <div class="col-sm-8">
                    <?php echo $id; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                Дата прихода
                </div>
                <div class="col-sm-8">
                    <?php echo $ord_date = date("d.m.Y", strtotime($date)); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    Примечание
                </div>
                <div class="col-sm-8">
                    <?php echo $note; ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- End card head information -->


<div class="prod_list">
    <div class="container-fluid">
        <table id="myTable" class="table table-hover">
        <thead>
            <tr class="w600">
                <td>№</td>
                <td>Наименование товаров</td>
                <td>Количество</td>
                <td>Ед. изм.</td>
                <!-- <td>Срок годности</td> -->
                <td>Цена</td>
                <td>Сумма</td>
                <td><span class="glyphicon glyphicon-edit"></span></td>
                <td><span class="glyphicon glyphicon-trash"></span></td>
            </tr>
        </thead>
            <tbody>
                <?php     
                $n = 0;    
                while ($row = mysqli_fetch_array($rs_result)) {    
                $n++;

                $name = get_prod_name($connect, $row["prod_name"]);
                
                $query = "SELECT * FROM products_tbl WHERE name='$name[name]'";  
                $unit_result = mysqli_query ($connect, $query);
                    if(!$unit_result)
                    die(mysqli_error($connect));
                $unit_name = mysqli_fetch_assoc($unit_result);
                $unit_name =  $unit_name['unit'];
                ?> 
                <tr>
                    <td class="col-sm">
                        <span><?php echo $n; ?></span>
                    </td>
                    <td class="col-sm-4" >
                        <span><?php echo $name["name"]; ?></span>
                    </td>
                    <td class="col-sm-1">
                        <span><?php echo number_format($row['count_name'], 0, ',', ' '); ?></span>
                    </td>
                    <td class="col-sm-1">
                        <span><?php echo $unit_name; ?></span>
                    </td>
                    <!-- <td class="col-sm-1">
                        <?php echo $date = date("d.m.Y", strtotime($row['date_name'])); ?>
                    </td> -->
                    <td class="col-sm-1">
                        <span><?php echo number_format($row['price_name'], 0, ',', ' '); ?></span>                        
                    </td>
                    <td class="col-sm-2">
                        <span><?php echo number_format($row['total_name'], 0, ',', ' '); ?></span>
                    </td>
                    <td class="col-sm">
                        <a href="edit_action-sklad.php?note=<?php echo $note; ?>&&id=<?php echo $id; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>"><span class="glyphicon glyphicon-edit"></span></a>
                    </td>
                    <td class="col-sm">
                        <a onclick="return confirm('Удалить?')" href="edit_action-sklad.php?note=<?php echo $note; ?>&&id=<?php echo $id; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>&&del=ok"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                    <!-- <td class="col-sm-1">
                        <span><a href="edit_action.php?note=<?php echo $note; ?>&&date=<?php echo $date; ?>&&sum=<?php echo $sum; ?>&&id=<?php echo $id; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>">Редактировать</a></span>
                    </td> -->
                </tr>
                <?php     
                    };    
                ?>
                <tr>
                    <td class="w600"><span style="float:left;">Итого</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <!-- <td></td> -->
                    <td></td>
                    <td class="w600"><?php echo number_format($sum, 0, ',', ' '); ?></td>
                    <td></td>
                    <td>
                        <!-- <a href="edit_action_add.php?id=<?php echo $id; ?>&&payment_type=<?php echo $payment_type; ?>&&sale_agent=<?php echo $sale_agent; ?>&&contractor=<?php echo $contractor; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>"><span class="glyphicon glyphicon-plus-sign"></span></a> -->

                        <a href="edit_action_add-sklad.php?note=<?php echo $note; ?>&&id=<?php echo $id; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>"><span class="glyphicon glyphicon-plus-sign"></span></a>

                    </td>
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
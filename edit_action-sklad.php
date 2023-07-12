<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

// for delete order inside item 

if (isset($_GET['del']) && $_GET['del'] == 'ok') {
    $orid = $_GET['orid'];
    $pi = $_GET['pi'];
    $pn = $_GET['pn'];
    $cn = $_GET['cn'];
    $prn = $_GET['prn'];
    $dn = $_GET['dn'];


     $id = $_GET['id'];
     $note = $_GET['note'];
     $ord_date = $_GET['date'];



     $sum = get_sum_id_store($connect, $id);
     $sum_count = sum_count_store($connect, $id);


    if (del_store_item_tbl($connect, $pi)) {

        $sum = get_sum_id_store($connect, $orid);

        if (upd_store_sum($connect, $orid, $sum)) {
            header("Location: edit_pro-sklad.php?id=".$orid."&&date=".$ord_date."&&date=".$date."&&note=".$note."");
        }
    }

}

// delete



if (isset($_GET['pn'])) {
    $orid = $_GET['orid'];
    $pi = $_GET['pi'];
    $pn = $_GET['pn'];
    $cn = $_GET['cn'];
    $prn = $_GET['prn'];
    $dn = $_GET['dn'];


     $id = $_GET['id'];
     $note = $_GET['note'];
     $ord_date = $_GET['date'];


    //
    $sum = get_sum_id_store($connect, $id);
    $sum_count = sum_count_store($connect, $id);

}


if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {

    $orid=$_POST['orid'];
    $pi=$_POST['pi'];

    // qaytarish uchu card information
    $id = $_POST['id'];
    $note = $_POST['note'];
    $ord_date = $_POST['ord_date'];
    
    $date = $_POST['ord_date'];

    //maxsulot idsi
    $p_name=$_POST['prod_name'];
    

    // maxsulot soni
    $c_name=$_POST['count_name'];

    //maxsulot srok godnosti
    $d_name=$_POST['date_name'];        
  

    // maxsulot narxi
    $pr_name=$_POST['price_name'];
    

    $t_name = ($c_name * $pr_name);

    // $last_count = get_pi_last_count($connect, $pi);

    if (upd_store_item($connect, $orid, $pi, $p_name, $c_name, $pr_name, $d_name, $t_name)) {

        $sum = get_sum_id_store($connect, $orid);
        if (upd_store_sum($connect, $orid, $sum)) {
            header("Location: edit_pro-sklad.php?id=".$orid."&&date=".$ord_date."&&date=".$date."&&note=".$note."");
        }
    }
    
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
            <a href="edit_pro-sklad.php?note=<?php echo $note; ?>&&id=<?php echo $id; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>"><button type="button" class="btn btn-custom">Закрыть</button></a>
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
                    <?php echo $ord_date = date("d.m.Y", strtotime($ord_date)); ?>
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



<!-- Start prod list -->
<div class="prod_list prod_list__edit">
    <div class="container-fluid">
        <table class="table table-hover">
            <thead>
                <tr class="w600">
                    <td>№</td>
                    <td>Наименование товаров</td>
                    <td>Количество</td>
                    <td>Ед. изм.</td>
                    <!-- <td>Срок годности</td> -->
                    <td>Цена</td>
                    <td>Сумма</td>
                    <td></td>
                    <td></td>
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
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="horizntal-form" id="order_form">
                    <tr>
                        <td class="col-sm">
                            <span><?php echo $n; ?></span>
                        </td>
                        <td class="col-sm-4" >
                            <span><?php echo $name['name']; ?></span>
                            <input type="hidden" name="prod_name"  class="form-control" form="order_form" value="<?php echo $pn;?>"/>                  
                            <input type="hidden" name="price_name"  class="form-control" form="order_form" value="<?php echo $prn;?>"/>                  

                        </td>
                        <td class="col-sm-1">
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                                <input  required type="number" min="1" name="count_name"  class="form-control" form="order_form" value="<?php echo $cn;?>"/>
                            <?php
                                }else {
                            ?>
                                <span><?php echo number_format($row['count_name'], 0, ',', ' '); ?></span>
                            <?php
                                }
                            ?> 
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo $unit_name; ?></span>
                        </td>
                        <!-- <td class="col-sm-1"> -->
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                                <input required type="hidden" name="date_name" class="form-control" form="order_form" value="<?php echo $dn;?>"/>
                            <?php
                                }else {
                            ?>
                                <!-- <?php echo $ord_date_n = date("d.m.Y", strtotime($row['date_name'])); ?> -->
                            <?php
                                }
                            ?>                       
                        <!-- </td> -->
                        <td class="col-sm-1">
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                                <input required type="number" name="price_name" class="form-control" form="order_form" value="<?php echo $prn;?>"/>
                            <?php
                                }else {
                            ?>
                                <span><?php echo number_format($row['price_name'], 0, ',', ' '); ?></span>
                            <?php
                                }
                            ?> 
                            <input  type="hidden" name="orid"  form="order_form" value="<?php echo $orid;?>"/>
                            <input  type="hidden" name="pi"  form="order_form" value="<?php echo $pi;?>"/>
                            <input  type="hidden" name="note"  form="order_form" value="<?php echo $note;?>"/>
                            <input  type="hidden" name="ord_date"  form="order_form" value="<?php echo $ord_date;?>"/>
                        </td>
                        <td class="col-sm-2">
                            <span><?php echo number_format($row['total_name'], 0, ',', ' '); ?></span>
                        </td>
                        <td class="col-sm">
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                            <button type="submit" form="order_form" name="submit" value="Сохранить">
                                <span style="color:green;" class="glyphicon glyphicon-ok"></span>  
                            </button>
                            <!-- <input class="glyphicon glyphicon-edit" type="submit" form="order_form" name="submit" value="Сохранить" /> -->
                            <?php
                                }
                            ?>
                        </td>
                        <td class="col-sm">
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                            <a href="edit_pro-sklad.php?note=<?php echo $note; ?>&&id=<?php echo $id; ?>&&date=<?php echo $ord_date; ?>&&orid=<?php echo $id; ?>&&pi=<?php echo $row["id"]; ?>&&pn=<?php echo $row["prod_name"]; ?>&&cn=<?php echo $row["count_name"]; ?>&&dn=<?php echo $row["date_name"]; ?>&&prn=<?php echo $row["price_name"]; ?>&&sn=<?php echo $row["sale_name"]; ?>&&tn=<?php echo $row["total_name"]; ?>"><button type="button"><span class="glyphicon glyphicon-remove"></span></button></a>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                </form>
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
                    <td></td>
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
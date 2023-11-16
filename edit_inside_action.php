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
    $sn = $_GET['sn'];
    $st = $_GET['st'];
    $dn = $_GET['dn'];


    $id = $_GET['id'];
    $payment_type = $_GET['payment_type'];
    $sale_agent = $_GET['sale_agent'];
    $contractor = $_GET['contractor'];
    $ord_date = $_GET['date'];
    $ord_deliver_date = $_GET['del_date'];



    
    // $sum = get_sum_id_main($connect, $orid);
    $sum_count = sum_count_main($connect, $id);


    // Check if the id has more than one item in the table
    $result = mysqli_query($connect, "SELECT COUNT(*) AS item_count FROM main_ord__item_tbl WHERE order_id = '$orid'");
    $rowss = mysqli_fetch_assoc($result);
    $itemCount = $rowss['item_count'];

    if ($itemCount > 1) {
        if (del_main_ord_item_tbl($connect, $pi)) {

            $sum = get_sum_id_main($connect, $orid);
            
    
            if (upd_main_order_sum($connect, $orid, $sum)) {
                header("Location: edit_inside_order.php?id=".$orid."&&payment_type=".$payment_type."&&sale_agent=".$sale_agent."&&contractor=".$contractor."&&date=".$ord_date."&&del_date=".$ord_deliver_date."");
            }
        }
    } else {
        header("Location: edit_inside_order.php?id=".$orid."&&payment_type=".$payment_type."&&sale_agent=".$sale_agent."&&contractor=".$contractor."&&date=".$ord_date."&&del_date=".$ord_deliver_date."");
    }




}

// delete



if (isset($_GET['pn'])) {
    $orid = $_GET['orid'];
    $pi = $_GET['pi'];
    $pn = $_GET['pn'];
    $cn = $_GET['cn'];
    $prn = $_GET['prn'];
    $sn = $_GET['sn'];
    $st = $_GET['st'];
    $dn = $_GET['dn'];


    $id = $_GET['id'];
    $payment_type = $_GET['payment_type'];
    $sale_agent = $_GET['sale_agent'];
    $contractor = $_GET['contractor'];
    $ord_date = $_GET['date'];
    $ord_deliver_date = $_GET['del_date'];
    


    
    $sum = get_sum_id_main($connect, $id);
    $sum_count = sum_count_main($connect, $id);

}

$input_display = 'none';
$span_display = 'none';

if (isset($_GET['ed'])) {
    $input_display = true;
    $span_display = 'none';
}else {
    $input_display = 'none';
    $span_display = true;
}




if(isset($_POST['submit']) && $_POST['submit'] == 'edit_deliver_ok') {

    if (isset($_POST['delivery_date'])) {
        $delivery_date = $_POST['delivery_date'];
        $orid=$_POST['orid'];


        $payment_type = $_POST['payment_type'];
        $sale_agent = $_POST['sale_agent'];
        $contractor = $_POST['contractor'];
        $date = $_POST['ord_date'];

        if (upd_main_order_deliver_date($connect, $orid, $delivery_date)) {
            header("Location: edit_inside_order.php?id=".$orid."&&payment_type=".$payment_type."&&sale_agent=".$sale_agent."&&contractor=".$contractor."&&date=".$date."&&del_date=".$delivery_date."");
        }
        
    }
    

}





if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {

    
    

    $orid=$_POST['orid'];
    $pi=$_POST['pi'];


    $payment_type = $_POST['payment_type'];
    $sale_agent = $_POST['sale_agent'];
    $contractor = $_POST['contractor'];
    $date = $_POST['ord_date'];
    $delivery_date = $_POST['delivery_date'];


    //maxsulot idsi
    $p_name=$_POST['prod_name'];

    // maxsulot soni
    $c_name=$_POST['count_name'];
    // $d_name=$_POST['date_name'];        

    // maxsulot narxi
    $pr_name=$_POST['price_name'];

    //skidka
    $s_name=$_POST['sale_name'];

    //skidka type
    $s_type=$_POST['sale_type'];

    if ($s_type === 'percent') {
        $t_name = ($c_name * $pr_name) + ($c_name * $pr_name * $s_name) / 100;
    } 
    elseif ($s_type === 'sum') {
        $t_name = ($pr_name - (- $s_name)) * $c_name;
    } 

    
   

    $last_count = get_pi_last_count($connect, $pi);

    if (upd_main_ord_item($connect, $orid, $pi, $p_name, $c_name, $pr_name, $s_name, $s_type, $t_name)) {

        $sum = get_sum_id_main($connect, $orid);
        upd_main_order_sum($connect, $orid, $sum);

        header("Location: edit_inside_order.php?id=".$orid."&&payment_type=".$payment_type."&&sale_agent=".$sale_agent."&&contractor=".$contractor."&&date=".$date."&&del_date=".$delivery_date."");
    }
    
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
<!-- Container element to hold the snipping GIF -->
<div id="snipping-container"></div>

<?php include 'partSite/nav.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Редактировать заказ № <?php echo $id; ?></span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <a href="edit_inside_order.php?id=<?php echo $orid; ?>&&payment_type=<?php echo $payment_type; ?>&&sale_agent=<?php echo $sale_agent; ?>&&contractor=<?php echo $contractor; ?>&&date=<?php echo $ord_date; ?>&&del_date=<?php echo $ord_deliver_date; ?>"><button type="button" class="btn btn-custom">Закрыть</button></a>
        </div>
</div>

<!-- start card head information -->
<div class="container-fluid">
    <div class="card_head">
        <div class="card_head__wrapper">
            <div class="row">
                <div class="col-sm-4">
                Контрагент
                </div>
                <div class="col-sm-8">
                <?php $contractor_n = get_contractor($connect, $contractor);?><?php echo $contractor_n["name"]; ?>

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

            <div class="row" style="display:<?php echo $input_display; ?>">
                    <div class="col-sm-4">
                    Дата отгрузки
                    </div>
                    <div class="col-sm-8">
                        <div class="cutom_edit_delivery">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="edit_deliver_form">

                                <input required type="date" name="delivery_date"  class="form-control cutom_edit_delivery" form="edit_deliver_form" value="<?php echo $ord_deliver_date; ?>"/>
                                <input  type="hidden" name="orid"  form="edit_deliver_form" value="<?php echo $orid;?>"/>
                                <input  type="hidden" name="payment_type"  form="edit_deliver_form" value="<?php echo $payment_type;?>"/>
                                <input  type="hidden" name="sale_agent"  form="edit_deliver_form" value="<?php echo $sale_agent;?>"/>
                                <input  type="hidden" name="contractor"  form="edit_deliver_form" value="<?php echo $contractor;?>"/>
                                <input  type="hidden" name="ord_date"  form="edit_deliver_form" value="<?php echo $ord_date;?>"/>   

                                <div style='float:right; '>
                                    <button type="submit" form="edit_deliver_form" name="submit" value="edit_deliver_ok">
                                        <span style="color:green;" class="glyphicon glyphicon-ok"></span>  
                                    </button>
                                    <a href="edit_inside_order.php?id=<?php echo $orid; ?>&&payment_type=<?php echo $payment_type; ?>&&sale_agent=<?php echo $sale_agent; ?>&&contractor=<?php echo $contractor; ?>&&date=<?php echo $ord_date; ?>&&del_date=<?php echo $ord_deliver_date; ?>">
                                        <button type="button">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </a>
                                </div>
                            </form>
                                
                        </div>
                       
                    </div>
            </div>
            <div class="row" style="display:<?php echo $span_display; ?>">
                <div class="col-sm-4">
                Дата отгрузки
                </div>
                <div class="col-sm-8">
                    <?php echo $ord_deliver_date = date("d.m.Y", strtotime($ord_deliver_date)); ?>
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
<div class="prod_list prod_list__edit">
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
                    <td></td>
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
                        <td class="col-sm-1">
                            <span><?php echo number_format($row['price_name'], 0, ',', ' '); ?></span>                        
                        </td>
                        <td class="col-sm-1">
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                                <input required type="number" name="sale_name" class="form-control" form="order_form" value="<?php echo $sn;?>"/>
                            <?php
                                }else {
                            ?>
                                <span><?php echo $row["sale_name"]; ?></span>
                            <?php
                                }
                            ?> 
                            
                            <input  type="hidden" name="orid"  form="order_form" value="<?php echo $orid;?>"/>
                            <input  type="hidden" name="pi"  form="order_form" value="<?php echo $pi;?>"/>
                            <input  type="hidden" name="payment_type"  form="order_form" value="<?php echo $payment_type;?>"/>
                            <input  type="hidden" name="sale_agent"  form="order_form" value="<?php echo $sale_agent;?>"/>
                            <input  type="hidden" name="contractor"  form="order_form" value="<?php echo $contractor;?>"/>
                            <input  type="hidden" name="ord_date"  form="order_form" value="<?php echo $ord_date;?>"/> 
                            <input  type="hidden" name="delivery_date"  form="order_form" value="<?php echo $ord_deliver_date;?>"/> 
                        
                        </td>
                        <td class="col-sm-1">
                            
                            <?php 
                                if ($row["prod_name"] == $pn) {
                            ?>
                                <select name="sale_type" class="form-control" form="order_form" >
                                    <option value="<?php echo $row["sale_type"]; ?>"><?php echo $sale_type; ?></option>
                                    <option value="sum">сум</option>
                                    <option value="percent">%</option>
                                </select>

                            <?php
                                }else {
                            ?>
                               <span><?php echo $sale_type; ?></span>
                            <?php
                                }
                            ?> 


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
                            <a href="edit_inside_order.php?id=<?php echo $orid; ?>&&payment_type=<?php echo $payment_type; ?>&&sale_agent=<?php echo $sale_agent; ?>&&contractor=<?php echo $contractor; ?>&&date=<?php echo $ord_date; ?>&&del_date=<?php echo $ord_deliver_date; ?>"><button type="button"><span class="glyphicon glyphicon-remove"></span></button></a>
                            <!-- <input class="glyphicon glyphicon-edit" type="submit" form="order_form" name="submit" value="Сохранить" /> -->
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
                    <td></td>
                    <td></td>
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
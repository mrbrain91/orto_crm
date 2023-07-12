<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


$display = 'none';
$btn_display = 'none';
//---get supplier
$sql = "SELECT * FROM supplier_tbl";
$supplier_tbl = mysqli_query ($connect, $sql);
//---
if (isset($_POST['id_contractor'])) {
    $display = 'true';
    $btn_display = 'true';
    $id_contractor = $_POST['id_contractor'];

    $contractor = get_supplier($connect, $id_contractor); 

    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    


    $qr_start = "SELECT * FROM supplier WHERE id_supplier = '$id_contractor' AND order_date < '$from_date' ORDER BY order_date";
    $rs_qr_start = mysqli_query ($connect, $qr_start);
    
    $sum_debt_saldo = 0;
    $sum_main_prepayment_saldo = 0;

    while ($row_qr = mysqli_fetch_array($rs_qr_start)) {
        $sum_debt_saldo = $sum_debt_saldo + $row_qr['debt'];
        $sum_main_prepayment_saldo = $sum_main_prepayment_saldo + $row_qr['credit'];
    }


    if ($sum_debt_saldo > $sum_main_prepayment_saldo) {
        $sum_debt_saldo =  $sum_debt_saldo - $sum_main_prepayment_saldo;
        $sum_main_prepayment_saldo = 0;
    }elseif ($sum_debt_saldo < $sum_main_prepayment_saldo) {
        $sum_main_prepayment_saldo = $sum_main_prepayment_saldo - $sum_debt_saldo;
        $sum_debt_saldo = 0;
    }else {
        $sum_debt_saldo = 0;
        $sum_main_prepayment_saldo = 0;
    }


    $qr = "SELECT * FROM supplier WHERE id_supplier = '$id_contractor' AND order_date >= '$from_date' AND order_date <= '$to_date' ORDER BY order_date";
    $rs_qr = mysqli_query ($connect, $qr);


    
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
        <span class="right_cus">Акт-сверки</span>
    </div>    
</div>



<!-- Tab item -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a style="color: #666666;" class="nav-link" aria-current="page" href="act.php">Акт сверки с клиентами</a>
  </li>
  <li class="nav-item">
    <a style="border-bottom: 2px solid #5db85c; color: #666666;" class="nav-link" href="act_supplier.php">Акт сверки с поставщиками</a>
  </li>
</ul>
<!-- End tab item -->


<div class="toolbar">
        <div class="container-fluid">
           <!-- <a href="#"> <button type="button" class="btn btn-success">Взаимозачет</button> </a> -->
           <!-- <a href="add_order.php"> <button type="button" class="btn btn-primary">должники</button> </a> -->
          <input class="btn btn-success" type="submit" form="order_form" name="submit" value="сформировать акт" />
          <button style="display:<?php echo $btn_display; ?>" class="btn btn-info" onclick="exportTableToExcel('tblData', 'act')">скачать (.xls)</button>

        </div>
</div>

<section class="card_head dotedline">
    <div class="container-fluid">
        <form action="#" method="POST" class="horizntal-form" id="order_form">
            <div class="row">
                <div class="col-md-3">
                    <span>Поставщик</span>
                </div>
                <div class="col-md-2">
                        <span>Дата начала</span>
                </div>&ensp;
                <div class="col-md-2">
                        <span>Дата конца</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"> 
                    <select required class="normalize" name="id_contractor" form="order_form">
                        <option value="">Выберите: </option>
                        <?php    
                            while ($option_contractor = mysqli_fetch_array($supplier_tbl)) {    
                        ?>
                            <option value="<?php echo $option_contractor["id"];?>"><?php echo $option_contractor["name"]?></option>
                        <?php
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-2"> 
                    <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="from_date" form="order_form">
                </div>
                -
                <div class="col-md-2">
                    <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="to_date" form="order_form">
                </div>    
            </div>
        </form>
    </div>
</section>


<div class="all_table" style='display: <?php echo $display;?>' >
    <div class="container-fluid">
        <div class="table_wrap">
            <table id="tblData" class="table table-dark table-bordered act_td" style="width:70%; margin: 0 auto; margin-top: 30px;">
                    <tr>
                        <td class="non_border head_txt" colspan="6">
                            <span>Акт сверки</span> <br> 
                        
                            взаимных расчетов за период: <?php echo $date = date("d.m.Y", strtotime($from_date)); ?> - <?php echo $date = date("d.m.Y", strtotime($to_date)); ?> <br>
                            между ООО "ORTOPHARM" и <?php echo $contractor["name"];?> 
                            <!-- по договору Основной договор  -->
                        </td>
                    </tr>
                    <tr>
                        <td class="non_border" colspan="6"> Мы, нижеподписавшиеся, ООО "ORTOPHARM", с одной стороны, и <?php echo $contractor["name"];?>, с другой стороны, составили настоящий акт сверки в том, что состояние взаимных расчетов по данным учета следующее:</td>
                    </tr>
                    <tr>
                        <td colspan="3">По данным ООО "ORTOPHARM", сум.</td>
                        <td colspan="3">По данным <?php echo $contractor["name"];?>, сум.</td>
                    </tr>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Дебет</th>
                        <th scope="col">Кредит</th>
                        <th scope="col">Дата</th>
                        <th scope="col">Дебет</th>
                        <th scope="col">Кредит</th>
                    </tr>
            

                    
                    <tr>
                        <td class="ordernum">Сальдо начальное</th>
                        <td class="ordernum" scope="col"><?php echo number_format($sum_debt_saldo, 0, ',', ' ') ?></th>
                        <td class="ordernum" scope="col"><?php echo number_format($sum_main_prepayment_saldo, 0, ',', ' ') ?></th>
                        <td class="ordernum">Сальдо начальное</th>
                        <td class="ordernum" scope="col"><?php echo number_format($sum_main_prepayment_saldo, 0, ',', ' ') ?></th>
                        <td class="ordernum" scope="col"><?php echo number_format($sum_debt_saldo, 0, ',', ' ') ?></th>
                    </tr>   
                                
                    <?php     
                        $i = 0;
                        $sum_debt = 0;
                        $sum_prepayment = 0;
                        $row_display = 'true';
                        while ($row = mysqli_fetch_array($rs_qr)) {
                        $i++;
                        $sum_debt += $row["debt"];
                        $sum_prepayment += $row["credit"];
                        
                    ?> 

                    <tr>
                        <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>

                        <td><?php 
                            if ($row["debt"] == "0") {
                                echo '';
                            }else{
                                echo number_format($row["debt"], 0, ',', ' ');
                            }
                            ?>
                        </td>
                        <td><?php 
                            if ($row["credit"] == "0") {
                                echo '';
                            }else{
                                echo number_format($row["credit"], 0, ',', ' ');
                            }
                            ?>
                        </td>

                        <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>

                        <td><?php 
                            if ($row["credit"] == "0") {
                                echo '';
                            }else{
                                echo number_format($row["credit"], 0, ',', ' ');
                            }
                            ?>
                        </td>

                        <td><?php 
                            if ($row["debt"] == "0") {
                                echo '';
                            }else{
                                echo number_format($row["debt"], 0, ',', ' ');
                            }
                            ?>
                        </td>
                    </tr>

                    <?php       
                        };      
                        $display_non_debt = 'none';
                        $display_debt_1 = 'none';
                        $display_debt_2 = 'none';
                        $sum_last_debt = 0;
                        $sum_last_prepayment = 0;
                        if ($sum_debt_saldo != '0') {
                            $sum_debt_t = $sum_debt;
                            $sum_debt = $sum_debt + $sum_debt_saldo;
                        }else {
                            $sum_debt_t = $sum_debt;
                        }
                        
                        
                        if ($sum_main_prepayment_saldo != '0') {
                            $sum_prepayment_t = $sum_prepayment;
                            $sum_prepayment = $sum_prepayment + $sum_main_prepayment_saldo; 
                        }else {
                            $sum_prepayment_t = $sum_prepayment;
                        }

                        if ($sum_debt > $sum_prepayment) {
                            $sum_last_debt =  $sum_debt - $sum_prepayment;
                            
                        }else if ($sum_debt < $sum_prepayment) {
                            $sum_last_prepayment = $sum_prepayment - $sum_debt;
                        }
                        
                        if ($sum_last_debt == 0 AND $sum_last_prepayment == 0) {
                            $display_non_debt = "true";
                        }elseif ($sum_last_debt != 0) {
                            $display_debt_1 = "true";
                        }elseif ($sum_last_prepayment != 0) {
                            $display_debt_2 = "true";
                        }
                    ?>
                    <tr>
                        <td class="ordernum">Обороты за период:</td>
                        <td class="ordernum"><?php echo number_format($sum_debt_t, 0, ',', ' '); ?></td>
                        <td class="ordernum"><?php echo number_format($sum_prepayment_t, 0, ',', ' '); ?></td>
                        <td class="ordernum">Обороты за период:</td>
                        <td class="ordernum"><?php echo number_format($sum_prepayment_t, 0, ',', ' '); ?></td>
                        <td class="ordernum"><?php echo number_format($sum_debt_t, 0, ',', ' '); ?></td>
                    </tr>
                    <tr style="border-bottom-style: 1px solid green">
                        <td class="ordernum">Сальдо конечное:</th>
                        <td class="ordernum"><?php 
                        echo number_format($sum_last_debt, 0, ',', ' '); 
                        ?></td>
                        <td class="ordernum"><?php 
                        echo number_format($sum_last_prepayment, 0, ',', ' '); 
                        ?></td>
                        <td class="ordernum">Сальдо конечное:</th>
                        <td class="ordernum"><?php 
                        echo number_format($sum_last_prepayment, 0, ',', ' '); 
                        ?></td>
                        <td class="ordernum"><?php 
                        echo number_format($sum_last_debt, 0, ',', ' '); 
                        ?></td>
                    </tr>  
                    <tr class="non_border_lr">
                        <td colspan="6"> </td>
                    </tr>
                    <tr>
                    <tr class="non_border_all"  style="display: <?php echo $display_non_debt;?>">
                        <td class="ordernum" colspan="6">на <?php echo $date = date("d.m.Y", strtotime($to_date)); ?> задолженность отсутствует. </td>
                    </tr>
                    <tr class="non_border_all" style="display: <?php echo $display_debt_1;?>">
                        <td class="ordernum" colspan="6">на <?php echo $date = date("d.m.Y", strtotime($to_date)); ?> задолженность в ползу ООО "ORTOPHARM" 
                        <?php echo number_format($sum_last_debt, 0, ',', ' '); ?> (<?php echo str_price($sum_last_debt)?>) сум.
                    </td>
                    </tr>
                    <tr class="non_border_all" style="display: <?php echo $display_debt_2;?>">
                        <td class="ordernum" colspan="6">на <?php echo $date = date("d.m.Y", strtotime($to_date)); ?> задолженность в ползу <?php echo $contractor["name"];?>
                        <?php echo number_format($sum_last_prepayment, 0, ',', ' '); ?> (<?php echo str_price($sum_last_prepayment)?>) сум.
                    </td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
                    <tr class="non_border_all">
                        <td class="non_border_all"  colspan="3">от ООО "ORTOPHARM"</td>
                        <td colspan="3">От <?php echo $contractor["name"];?></td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
                    <tr class="non_border_all">
                        <td class="non_border_all" colspan="3">Директор</td>
                        <td colspan="3">Директор</td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
                    <tr class="non_border_all">
                        <td class="non_border_all" colspan="3">________________________________________________________</td>
                        <td colspan="3">________________________________________________________</td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
                    <tr class="non_border_all">
                        <td class="non_border_all" colspan="3">М.П.</td>
                        <td colspan="3">М.П.</td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
                    <tr class="non_border_all">
                        <td colspan="6"> </td>
                    </tr>
            </table>
        </div>
    </div>
</div>




<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>
<script>
$('.normalize').selectize();

function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}


</script>
<script src="js/snipping.js"></script>
</body>
</html>
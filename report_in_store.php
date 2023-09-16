<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


$display = 'none';
$btn_display = 'none';
//---get counterparties
$sql = "SELECT * FROM products_tbl";
$products_tbl = mysqli_query ($connect, $sql);
//---

if (isset($_POST['id_product'])) {
    $display = 'true';
    $btn_display = 'true';

    $contractor = get_supplier($connect, $id_contractor); 
    $id_product = $_POST['id_product'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];


    // Query to fetch specific columns from both tables, including the contractor by order ID     
    $qr_start = "SELECT oit.date_name, oit.count_name, oit.total_name, ot.supplier_id
            FROM order_item_product AS oit
            JOIN order_tbl AS ot ON oit.order_id = ot.id
            WHERE oit.prod_name = $id_product AND oit.date_name BETWEEN '$from_date' AND '$to_date' AND oit.store_itm_sts IN (1)
            ORDER BY oit.date_name";


    $rs_qr_start = mysqli_query($connect, $qr_start);
    if (!$rs_qr_start) {
        die("Query Error: " . mysqli_error($connect));
    }
}

$currentDate = date("Y-m-d");
$endOfDay = '23:59:59';
$dateValue = $currentDate . ' ' . $endOfDay;


?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous"> -->
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
        <span class="right_cus">Отчеты</span>
    </div>
</div>



<!-- Tab item -->
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a style="color: #666666;" class="nav-link active" aria-current="page" href="report.php">отчет о продажах продукции</a>
  </li>
  <li class="nav-item">
    <a style="color: #666666; border-bottom: 2px solid #5db85c;" class="nav-link" href="report_in_store.php">отчет о получении товара</a>
  </li>
</ul>
<!-- End tab item -->



<div class="toolbar">
        <div class="container-fluid">
           <!-- <a href="#"> <button type="button" class="btn btn-success">Взаимозачет</button> </a> -->
           <!-- <a href="add_order.php"> <button type="button" class="btn btn-primary">должники</button> </a> -->
          <input class="btn btn-success" type="submit" form="order_form" name="submit" value="сформировать отчет" />
          <button style="display:<?php echo $btn_display; ?>" class="btn btn-info" onclick="exportTableToExcel('tblData', 'act')">скачать (.xls)</button>

        </div>
</div>

<section class="card_head dotedline">
    <div class="container-fluid">
        <form action="#" method="POST" class="horizntal-form" id="order_form">
            <div class="row">
                <div class="col-md-3">
                    <span>Продукции</span>
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
                    <select required class="normalize" name="id_product" form="order_form">
                        <option value="">Выберите: </option>
                        <?php    
                            while ($option_products = mysqli_fetch_array($products_tbl)) {    
                        ?>
                            <option value="<?php echo $option_products["id"];?>"><?php echo $option_products["name"]?></option>
                        <?php
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-2"> 
                    <input required type="date" value="2023-01-01" class="form-control" name="from_date" form="order_form">
                </div>
                -
                <div class="col-md-2">
                    <input required type="datetime-local" value="<?php echo $dateValue; ?>" class="form-control" name="to_date" form="order_form">
                    <!-- <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="to_date" form="order_form"> -->
                </div>    
            </div>
        </form>
    </div>
</section>


<div class="all_table" style='display: <?php echo $display;?>' >
    <div class="container-fluid">
        <div class="table_wrap">
        

    <!-- <center><p>Product ID: <?php echo $id_product; ?></p></center> -->
    <center><p style='font-size: 14px; font-weight: 600;'> <?php $name = get_prod_name($connect, $id_product); echo $name['name']; ?></p></center>

    <center style='color:green;'>поступления за период:  <b><?php echo $date = date("d.m.Y", strtotime($from_date)); ?> - <?php echo $date = date("d.m.Y", strtotime($to_date)); ?></center> 
    <table id="tblData" class="table table-striped table-bordered act_td" style="width:70%; margin: 0 auto; margin-top: 30px;">

        <tr>
            <th>Дата</th>
            <th>Доставщик</th>
            <th>Количество</th>
            <th>Сумма</th>
        </tr>
        <?php
        if ($rs_qr_start) {
            $total_count  = 0;
            $total_sum  = 0;
            while ($row = mysqli_fetch_assoc($rs_qr_start)) {
                $total_count += $row["count_name"];
                $total_sum += $row["total_name"];
                echo "<tr style='font-weight: 400;'>";
                    echo "<td>" . $date = date("d.m.Y", strtotime($row["date_name"])) . "</td>";

                    $user = get_supplier($connect, $row["supplier_id"]);


                    echo "<td>" . $user["name"] . "</td>";

                    echo "<td>" . number_format($row["count_name"], 0, ',', ' ') . "</td>";
                    

                    echo "<td>" . number_format($row["total_name"], 0, ',', ' ') . "</td>";

                echo "</tr>";
            }
            // Free result set
            // mysqli_free_result($result);
        } else {
            echo "<tr><td colspan='4'>No data found for the specified criteria.</td></tr>";
        }
        echo "<tr>";
            echo "<td></td>";
            echo "<td>Итого</td>";
            echo "<td>" . number_format($total_count, 0, ',', ' ') . "</td>";

            echo "<td>" . number_format($total_sum, 0, ',', ' ') . "</td>";

        echo "</tr>";
        ?>
        
    </table><br><br><br><br>
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
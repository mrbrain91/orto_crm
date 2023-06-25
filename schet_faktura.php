<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $contractor_id = $_GET['contractor_id'];
    $contractor = get_contractor($connect, $contractor_id);

    $query = "SELECT * FROM main_ord__item_tbl WHERE order_id='$id'";  
    $rs_result = mysqli_query ($connect, $query);
    

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
        <span class="right_cus">Просмотр счет фактура</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <button onclick="window.close()" type="button" class="btn btn-custom">Закрыть</button>
            <button class="btn btn-custom " onClick="window.print()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> печать</button>
        </div>
</div>


<div id="section-to-print" class="invoice">
    
    <span class="invoice__title"> Счет фактура </span>
    <span class="invoice__title"> № OP2023-4-6 от 11.05.2023 </span>
    <span class="invoice__title"> к договору № OP2023-4 от 16.01.2023 </span>
    <!-- start card head information -->
    <div class="container-fluid">
        <div class="card_head">
            <div class="card_head__wrapper">
                <div class="row">
                    <div class="col-sm-4">
                    Поставщик:
                    </div>
                    <div class="col-sm-8">
                        ORTO PHARM MCHJ 

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                    Адрес:
                    </div>
                    <div class="col-sm-8">
                        Богишамол кучаси, 160 уй
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Идентификационный номер<br>
                    (ИНН)<br>
                    </div>
                    <div class="col-sm-8">
                    305478097
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Регистрационный код плательщика НДС:
                    </div>
                    <div class="col-sm-8">
                    326040033952
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Р/С:
                    </div>
                    <div class="col-sm-8">
                    20208000400869363001
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    МФО:
                    </div>
                    <div class="col-sm-8">
                    01071
                    </div>
                </div>

            </div>
            <div class="card_head__wrapper">
                <div class="row">
                    <div class="col-sm-4">
                    Поставщик:
                    </div>
                    <div class="col-sm-8">
                        <?php echo $contractor["name"]; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                    Адрес:
                    </div>
                    <div class="col-sm-8">
                        <?php echo $contractor["address"]; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Идентификационный номер<br>
                    (ИНН)<br>
                    </div>
                    <div class="col-sm-8">
                        <?php echo $contractor["inn"]; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Регистрационный код плательщика НДС:
                    </div>
                    <div class="col-sm-8">
                    <?php echo $contractor["nds"]; ?>
                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    Р/С:
                    </div>
                    <div class="col-sm-8">
                        <?php echo $contractor["raschetny_schet"]; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    МФО:
                    </div>
                    <div class="col-sm-8">
                        <?php echo $contractor["mfo"]; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End card head information -->

    <!-- Start prod list -->
    <div class="prod_list" >
        <div class="container-fluid">
            <table class="table table-hover">
                <thead>
                    <tr class="w600" style="text-align:center;">
                        <td>№</td>
                        <td>Наименование товаров</td>
                        <td>Единица измерения</td>
                        <td>Количество</td>
                        <td>Цена</td>
                        <td>Стоимость поставки</td>
                    </tr>
                    
                </thead>
                <tbody>
                    <tr style="text-align:center;">
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;">1</td>
                        <td style="text-align:center;">2</td>
                        <td style="text-align:center;">3</td>
                        <td style="text-align:center;">4</td>
                        <td style="text-align:center;">5</td>
                    </tr>
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
                        <td style="text-align:center;" class="col-sm-1" >
                            <span><?php echo $n; ?></span>
                        </td>
                        <td class="col-sm-4" style="text-align:left;">
                            <span><?php echo $name['name']; ?></span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo $unit_name; ?></span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo number_format($row['count_name'], 0, ',', ' '); ?>.0000</span>
                        </td>
                        <td class="col-sm-1">
                            <span><?php echo number_format($row['price_name'], 0, ',', ' '); ?>.00</span>
                            
                        </td>
                        <td class="col-sm-2">
                            <span><?php echo number_format($row['total_name'], 0, ',', ' '); ?></span>

                        </td>

                    </tr>
                    
                    <?php     
                        };    
                    ?>
                    <tr>
                        <td class="w600"><span style="float:left;">Итого</span></td>
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
    <div class="invoice__bottom">
        <div class="invoice__bottom_item">
            <span>Руководитель __________________________ SHARIPOV JASUR ERKINOVICH</span><br><br><br>
            <span>Главный бухгалтер: ____________________ SHARIPOV JASUR ERKINOVICH</span><br><br><br>
            <span>Товар отпустил: ________________________ QULMURODOV FARHOD</span> 
        </div>
        <div class="invoice__bottom_item">
            <span>Получил: _________________________________________________________</span><br>
            <span>(подписъ покупателя или уполномоченного представителя)</span><br><br>
            <span>Довернность: _____________________________________________________</span><br><br><br>
            <span>_____________________________________________________________________</span><br>
            <span>ФИО покупателя</span> 
        </div>
    </div>
    
   
</div>



<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>


</body>
</html>
<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}
 

$query = "SELECT id_counterpartie, SUM(prepayment) as total_prepayment, SUM(debt) AS total_debt, SUM(main_prepayment) AS total_main_prepayment FROM debts GROUP BY id_counterpartie";
$rs_result = mysqli_query ($connect, $query);


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
        <span class="right_cus">Взаиморасчеты с клиентами</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
           <!-- <a href="booked_payment_list.php"> <button type="button" class="btn btn-primary">история взаимарасчетов</button> </a> -->
        </div>
</div>
<div id="section-to-print">
    <div class="all_table">
        <div class="container-fluid">
            <table class="table table-hover" style="margin-bottom:0; border-collapse:collapse;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Контрагент</th>
                    <th scope="col">ИНН</th>
                    <!-- <th scope="col">Долг</th> -->
                    <!-- <th scope="col">Предоплата</th> -->
                    <th scope="col" style="text-align:right;">Сумма</th>
                </tr>
            </thead>
            <tbody>
    <?php
        $i = 0;
        $data = array(); // Array to store the row data

        while ($row = mysqli_fetch_array($rs_result)) {
            $last_debt = $row['total_debt'] - $row['total_prepayment'];
            $last_tot = $row['total_main_prepayment'] - $row['total_prepayment'];
            $sum = $last_tot - $last_debt;

            $sum_last_debt += $last_debt;
            $sum_last_tot += $last_tot;
            $balance_sum += $sum;

            if ($last_debt == 0 && $last_tot == 0) {
                $display = 'none';
                $i++;
            } else {
                $display = 'true';
                $i++;
            }

            // Store row data in the array
            $data[] = array(
                'i' => $i,
                'sum' => $sum,
                'row' => $row
            );
        }

        // Sort the array based on the 'sum' value in ascending order
        usort($data, function($a, $b) {
            if ($a['sum'] < 0 && $b['sum'] >= 0) {
                return -1; // Negative values first
            } elseif ($a['sum'] >= 0 && $b['sum'] < 0) {
                return 1; // Positive values after negative values
            } else {
                return $a['sum'] - $b['sum']; // Sort by sum in ascending order
            }
        });

        // Iterate through the sorted data to generate the table rows
        foreach ($data as $item) {
            $i = $item['i'];
            $sum = $item['sum'];
            $row = $item['row'];
    ?>

    <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
        <td><?php echo $i; ?></td>
        <td style="background-color:<?php echo $bg_clr;?>"><?php $user = get_contractor($connect, $row["id_counterpartie"]); echo $user["name"];?></td>
        <td><?php echo $user['inn']?></td>
        <td class="text-right"><?php echo number_format($sum, 0, ',', ' '); ?></td>
    </tr>
            <tr>
                <td colspan="12" style="border:0px; background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <!-- Additional content for the expanded row -->
                </td>
            </tr>

    <?php
        }
    ?>
    </tbody>
    <table class="table" style="background-color:#ebf0ff; border-left: 4px solid #7396ff;">
        <tr>
            <!-- <td style="text-align:center;">Количество: <?php echo number_format($i, 0, ',', ' '); ?></td> -->
            <!-- <td style="text-align:center;">Сумма долгов: <?php echo number_format($sum_last_debt, 0, ',', ' '); ?></td> -->
            <!-- <td style="text-align:center;">Сумма предоплат: <?php echo number_format($sum_last_tot, 0, ',', ' '); ?></td> -->
            <td style="text-align:right;">Итоговая сумма: <?php echo number_format($balance_sum, 0, ',', ' '); ?></td>
        </tr>
    </table>
</div>
    </div>
</div>






<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>

<script src="js/snipping.js"></script>
</body>
</html>
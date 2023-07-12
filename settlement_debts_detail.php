<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

if (isset($_GET['id']) and isset($_GET['prepayment'])) {
    $id = $_GET['id'];
    $prepayment = $_GET['prepayment'];
}

$query = "SELECT * FROM debts WHERE id_counterpartie='$id' AND debt>0 ORDER BY id DESC";
$rs_result = mysqli_query ($connect, $query);


$display = "none";

if(isset($_POST['submit']) && $_POST['submit'] == 'завершить') {
        $total_count = 0; 
        foreach($_POST['payment'] as $row => $value){
            $payment=$_POST['payment'][$row];
            $total_count += $payment;
        }
        if ($prepayment >= $total_count) {
            foreach($_POST['payment'] as $row => $value){

                $order_id=$_POST['order_id'][$row];
                $payment=$_POST['payment'][$row];
                $id_counterpartie=$_POST['id_counterpartie'][$row];
    
    
                if ($payment > 0 ) {
                    $sql = "INSERT INTO `debts` (`order_id`, `prepayment`, `id_counterpartie`)  VALUES ('".$order_id."','".$payment."','".$id_counterpartie."');";
                    mysqli_query($connect, $sql);
                }
                    
            }
            redirect("settlements_clients.php"); 
        }else {
           $display = 'true';
        }
}


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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/4.6.0/autoNumeric.js" integrity="sha512-/lbeISSLChIunUcgNvSFJSC+LFCZg08JHFhvDfDWDlY3a/NYb/NPKOcfDte3aA6E3mxm9a3sdxvkktZJSCpxGw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        <span class="right_cus">Перерасчет</span>
        <div class="alert alert-danger notification" role="alert" style="display:<?php echo $display; ?>;">
            !!! Сумма оплаты должна быть меньше или равно <span><?php echo number_format($prepayment, 0, ',', ' '); ?></span>
        </div>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
           <!-- <a href="#"> <button type="button" class="btn btn-success">Взаимозачет</button> </a> -->
           <input class="btn btn-primary" type="submit" form="order_form" name="submit" value="завершить" />
           <a href="settlements_clients.php"> <button type="button" class="btn btn-custom">закрыть</button> </a>
            <input  type="text"  id="prepay"  value="<?php echo $prepayment;?> " readonly><span style="float: right; font-weight: 600; margin: 9px 10px 0px 0px;">Предоплата:</span>
        </div>
</div>


<div class="all_table">
    <div class="container-fluid">
        <form action="#"  method="POST" class="horizntal-form" id="order_form">
            <table class="table table-hover">
            <thead>
                <tr>
                <th scope="col">Н/З</th>
                <th scope="col">Контрагент</th>
                <th scope="col">Дата заказа</th>
                <th scope="col">Тип оплаты</th>
                <th scope="col">Долг</th>
                <th class="w20" scope="col">Оплата</th>
                <th scope="col" class="w60px"></th>
                

                </tr>
            </thead>
            <tbody>

                <?php     
                    $i = 0;
                    while ($row = mysqli_fetch_array($rs_result)) {
                    $i++;

                    $ord_i = $row['order_id'];

                    $query1 = "SELECT SUM(prepayment) AS total_prepayment FROM debts WHERE order_id='$ord_i' GROUP BY order_id";
                    $row1 = mysqli_fetch_assoc(mysqli_query($connect, $query1));
                    $total_prepayment = $row1['total_prepayment'];

                    $query2 = "SELECT SUM(debt) AS total_debt FROM debts WHERE order_id='$ord_i' GROUP BY order_id";
                    $row2 = mysqli_fetch_assoc(mysqli_query($connect, $query2));
                    $total_debt = $row2['total_debt'];

                    $balance = $total_debt - $total_prepayment;


                    if ($balance == 0) {
                        $display = 'none';
                      }else {
                        $display = 'true';
                      }
                    
                ?> 

                <tr style="display:<?php echo $display; ?>;" data-toggle="collapse" data-target="#hidden_<?php echo $i;?>">
                    <td class="custom_td"><?php echo $row['order_id']?></td>
                    
                    <input type="hidden" value='<?php echo $row['order_id']?>' name="order_id[]" form="order_form">
                    <input type="hidden" value='<?php echo $row['id_counterpartie']?>' name="id_counterpartie[]" form="order_form">
                    <td class="custom_td"><?php $user = get_contractor($connect, $row["id_counterpartie"]); echo $user["name"];?></td>
                    <td class="custom_td"><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>
                    <td class="custom_td"><?php echo $row['payment_type']?></td>
                    <td class="custom_td"><a style="color:#333333;" href="settlement_order_detail.php?order_id=<?php echo $row['order_id'];?>&&id=<?php echo $row['id_counterpartie'];?>&&prepayment=<?php echo $prepayment;?>"><?php echo number_format($balance, 0, '.', ' ');?></a></td>
                    <td><input onblur="changeInput(<?php echo $i;?>);"  required min="0" max="<?php echo $balance?>" id="<?php echo $i;?>" value='0' class="form-control custom_input" name="payment[]" form="order_form"></td>
                    <td><button type="button"  onclick="change(<?php echo $balance?>,<?php echo $i;?>)"  class="btn btn-custom"><i class="fa fa-check" aria-hidden="true"></i></button></td>
                </tr>
   
                <?php       
                    };     
                ?>

            </tbody>
            </table>
        </form>
    </div>
</div>




<div class="container-fluid">

 <?php 
//  include 'partSite/modal.php'; 
 ?> 

</div>


<script>


    const autoNumericOptionsEuro = {
    decimalPlaces: '0',
    digitGroupSeparator        : ' ',
    decimalCharacter           : ',',
    decimalCharacterAlternative: '.',
    // roundingMethod: AutoNumeric.options.roundingMethod.halfUpSymmetric,
    };

    // Initialization
    // new AutoNumeric.multiple('.autonumber', autoNumericOptionsEuro);


                    

    const prepay = document.getElementById('prepay');
    prepay.value = numberWithSpaces(prepay.value);

    
    function change(value,id){
        const valueNumber = numberWithoutSpaces(prepay.value);
        document.getElementById(id).setAttribute('value',value);
        prepay.value = numberWithSpaces(Number(valueNumber) - Number(value));
    }

    // function changeSeparator(id) {
    //     spaceNum = numberWithSpaces(document.getElementById(id).value);
    //     document.getElementById(id).setAttribute('value',spaceNum);
    // }

    function changeInput(id){
        const valueNumber = numberWithoutSpaces(prepay.value);
        const valueInput = numberWithoutSpaces(document.getElementById(id).value);
        prepay.value = numberWithSpaces(Number(valueNumber) - Number(valueInput));
    }

   




    function numberWithSpaces(nr) {
        return nr.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
    function numberWithoutSpaces(nr) {
        return nr.toString().replace(/ /g,"");
    }


    

</script>


<script src="js/snipping.js"></script>
</body>

</html>
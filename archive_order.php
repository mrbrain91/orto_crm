<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}


// Retrieve the notification1 message from the URL parameter
$message = isset($_GET['message']) ? $_GET['message'] : '';

//---get counterparties
$sql = "SELECT * FROM counterparties_tbl";
$counterparties_tbl = mysqli_query ($connect, $sql);


// for count
$count_query = "SELECT count(*) as allcount FROM main_ord_tbl WHERE order_status='4' ORDER BY id DESC";
$count_result = mysqli_query($connect,$count_query);
$count_fetch = mysqli_fetch_array($count_result);
$postCount = $count_fetch['allcount'];
$limit = 15;

$display_sts_filer_on = 'none';


// filter form 
if (isset($_POST['id_contractor']) AND isset($_POST['from_date']) AND isset($_POST['to_date'])) {
    $id_cont = $_POST['id_contractor'];
    $fr_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
 
    $bg_sts = '#ebf0ff';
    $display_sts = 'none';
    $display_sts_filer_on = 'true';
    
    
     $query = "SELECT * FROM main_ord_tbl WHERE contractor = '$id_cont' AND order_status='4' AND ord_date >= '$fr_date' AND ord_date <= '$to_date' ORDER BY id DESC";
 
     $all_debt_query = "SELECT sum(transaction_amount) as all_debt, count(id) as allcount FROM main_ord_tbl WHERE contractor = '$id_cont' AND order_status='4' AND ord_date >= '$fr_date' AND ord_date <= '$to_date' ORDER BY id DESC";
    
  
 
 }
 else {
     //list all
     $query = "SELECT * FROM main_ord_tbl WHERE order_status='4' ORDER BY id desc LIMIT 0,".$limit;
     $all_debt_query = "SELECT sum(transaction_amount) as all_debt, count(id) as allcount FROM main_ord_tbl WHERE order_status='4'";
     
     $display_true = 'true';
     $display_none = 'none';
 
 
 
 
 }

 // for count/count
$all_debt_result = mysqli_query ($connect, $all_debt_query);
$all_debt_fetch = mysqli_fetch_array($all_debt_result);
$all_debt = $all_debt_fetch['all_debt'];
$all_count = $all_debt_fetch['allcount'];



if ($all_count < $limit) {
    $limit  = $all_count;
  }


//for list 
$rs_result = mysqli_query ($connect, $query);
$span = 'none';
if(mysqli_num_rows($rs_result) == 0) {
    $span = true;
} 





?>


<!DOCTYPE html>
<html lang="en">
<head>
  
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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

<?php include 'partSite/nav.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Архив заказов</span>
    </div>    
</div>


<div class="toolbar">
        <div class="container-fluid">
            <div class="toolbar_wrapper">
                <div>
                    
                <?php
                                        // Add the following code wherever you want to display the notification1
                    if (!empty($message)) {
                        echo '<div id="notification1" class="notification1">' . $message . '</div>';
                    }
                ?>
                    <!-- <a href="add_order.php"> <button type="button" class="btn btn-success">Добавить</button> </a> -->
                </div>
                <div class="filter-container">
                        <div style="background-color:<?php echo $bg_sts;?>" class="filter-container-item first" data-toggle="modal" data-target="#filter">
                        <span class="glyphicon glyphicon-filter"></span>
                        </div>
                        <div style="display:<?php echo $display_sts;?>" class="filter-container-item">
                            <span><span id="row_c"><?php echo $limit; ?></span> / <?php echo $all_count; ?></span>
                        </div>
                        <div style="display:<?php echo $display_sts_filer_on;?>"class="filter-container-item">
                            <span><?php echo $all_count?> / <?php echo $all_count; ?></span>
                        </div>
                        <div style="display:<?php echo $display_sts;?>" class="filter-container-item">
                            <div class="loadmore">
                                
                                <button class="btn btn-outline-info" type="button" id="loadBtn" value="+10"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                <button style="display:none;" class="btn btn-outline-info" type="button" id="endBtn" value="+10"><span class="glyphicon glyphicon-ok"></span></button>
                                <input type="hidden" id="row" value="0">
                                <input type="hidden" id="postCount" value="<?php echo $postCount; ?>">
                            </div>
                        </div>
                    </div>  
            </div>
          
        </div>
</div>



<div class="all_table">
    <div class="container-fluid">
        <table class="table table-hover" style="border-collapse:collapse;">
        <thead>
            <tr>
                <th scope="col">Н/З</th>
                <th scope="col">Контрагент</th>
                <th scope="col">Торговый представитель</th>
                <th scope="col">Дата заказа</th>
                <th scope="col">Дата отгрузки</th>
                <th scope="col">Тип оплаты</th>
                <th scope="col">Сумма сделки</th>
                <th scope="col">Состояние</th>
            </tr>
        </thead>
        <tbody class="postList">
        <tr style="display:<?php echo $span; ?>; text-align: center;">
            <td>
               Нет записи
            </td>
        </tr>
            
<?php     
    $i = 0;
    while ($row = mysqli_fetch_array($rs_result)) {
    $i++;

?> 
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $user = get_contractor($connect, $row["contractor"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php $user = get_user($connect, $row["sale_agent"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_date"])); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_deliver_date"])); ?></td>
            <td><?php echo $row["payment_type"]; ?></td>
            <td><?php echo number_format($row['transaction_amount'], 0, '.', ' '); ?></td>
            <td><span class="status archived">Архив</span></td>
        </tr>
        <tr >
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="inside_archive_order.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["ord_date"]; ?>&&del_date=<?php echo $row["ord_deliver_date"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="action.php?restore_id=<?=$row['id']?>"><button onclick="return confirm('Восстановить?')" class="btn btn-custom">Восстановить</button> </a>
            </div> </td>
        </tr>
        
<?php       
    };    
?>      
        </tbody>
        </table>
        <table class="table" style="background-color:#ebf0ff; border-left: 4px solid #7396ff;">
            <tr>
                <td style="text-align:left;">Кол-во сделок: <?php echo number_format($all_count, 0, ',', ' '); ?>
                <span style="margin-left:100px;">Сумма сделок: <?php echo number_format($all_debt, 0, ',', ' '); ?></span>
                
                </td>
                <td style="text-align:left;"></td>
            </tr>
        </table>
    </div>
</div>

<div class="container-fluid">
    <?php include 'partSite/modal.php'; ?>
</div>


<!-- Modal filter-->
<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <span>ФИЛЬТР</span>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST" id="filer_cencel">
            <input type="hidden" name="filer_cencel" form="filer_cencel" value="1">
        </form>
      <form action="#" method="POST" class="horizntal-form" id="filer_form">
            <div class="row">
                <div class="col-md-6">
                    <span>Контрагент</span>
                </div>
                <div class="col-md-3">
                        <span>Дата начала</span>
                </div>
                <div class="col-md-3">
                        <span>Дата конца</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6"> 
                    <select required class="normalize" name="id_contractor" form="filer_form">
                        <option value="">выберите</option>
                        <?php    
                            while ($option_contractor = mysqli_fetch_array($counterparties_tbl)) {    
                        ?>
                            <option value="<?php echo $option_contractor["id"];?>"><?php echo $option_contractor["name"]?></option>
                        <?php
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-3"> 
                    <input required type="date" value="2023-01-01" class="form-control" name="from_date" form="filer_form">
                </div>
                
                <div class="col-md-3">
                    <input required type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="to_date" form="filer_form">
                </div>    
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" form="filer_form">Отфильтровать</button>
        <button type="submit" class="btn btn-secondary" form="filer_cencel">Показать все</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">закрыть</button>
      </div>
    </div>
  </div>
</div>  
<!-- END MODAL -->



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>



<script>

// Display the notification1 for 5 seconds before fading out
setTimeout(function() {
    var notification1 = document.getElementById('notification1');
    if (notification1) {
        notification1.style.opacity = 1;
        (function fadeOut() {
            if ((notification1.style.opacity -= .1) < 0) {
                notification1.style.display = "none";
            } else {
                setTimeout(fadeOut, 50);
            }
        })();
    }
}, 5000);




    $('.accordian-body').on('show.bs.collapse', function () {
    $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        //// .collapse('toggle')
})



$('.normalize').selectize();

$(document).ready(function () {
    $(document).on('click', '#loadBtn', function () {       
      var row = Number($('#row').val());
      var count = Number($('#postCount').val());
      var limit = 15;
      var i = <?php echo $i;?>;

      row = row + limit;
    
      $('#row').val(row);
      $("#loadBtn").val('Loading...');
 
      $.ajax({
        type: 'POST',
        url: 'loadmore-data.php?otkont=1',
        data: 'rowarchive=' + row +  '&i=' + i,
        success: function (data) {
          var rowCount = row + limit;
          $("#row_c").text(rowCount);
  
          $('.postList').append(data);
          if (rowCount >= count) {
             $('#loadBtn').css("display", "none");
             $('#endBtn').css("display", "block");
             $("#row_c").text(count);
          } else {
            $("#loadBtn").val('+10');
          }
        }
      });
    });
  });

</script>

</body>
</html>
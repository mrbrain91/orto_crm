<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}




//---get counterparties
$sql = "SELECT * FROM supplier_tbl";
$supplier_tbl = mysqli_query ($connect, $sql);

// for count
$count_query = "SELECT count(*) as allcount FROM order_tbl ORDER BY id DESC";
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
    
    
     $query = "SELECT * FROM order_tbl WHERE supplier_id = '$id_cont' AND date_order >= '$fr_date' AND date_order <= '$to_date' ORDER BY id DESC";
 
     $all_debt_query = "SELECT sum(sum_order) as all_debt, count(id) as allcount FROM order_tbl WHERE supplier_id = '$id_cont' AND date_order >= '$fr_date' AND date_order <= '$to_date' AND status_order = 1 ORDER BY id DESC";
}
 else {
     //list all
     $query = "SELECT * FROM order_tbl ORDER BY id desc LIMIT 0,".$limit;
     $all_debt_query = "SELECT sum(sum_order) as all_debt, count(id) as allcount FROM order_tbl WHERE status_order = 1";
     
     $display_true = 'true';
     $display_none = 'none';
 }

 // for count/count
$all_debt_result = mysqli_query($connect, $all_debt_query);
$all_debt_fetch = mysqli_fetch_array($all_debt_result);
$all_debt = $all_debt_fetch['all_debt'];
$all_count = $all_debt_fetch['allcount'];



if ($all_count < $limit) {
    $limit  = $all_count;
}


//for list 
$rs_result = mysqli_query ($connect, $query);

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
<!-- Container element to hold the snipping GIF -->
<div id="snipping-container"></div>

<?php include 'partSite/nav-sklad.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Приход на склад</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <div class="toolbar_wrapper">
                <div>
                    <a href="add_pro-sklad.php"> <button type="button" class="btn btn-success">Добавить</button> </a>
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
                <th style="width:10%;" scope="col">Номер прихода</th>
                <th scope="col">Доставщик</th>
                <th scope="col">Сумма</th>
                <th scope="col">Дата прихода</th>
                <th scope="col">Состояние</th>
            </tr>
        </thead>
        <tbody class="postList">
            
<?php     
    $i = 0;
    while ($row = mysqli_fetch_array($rs_result)) {
    $i++;
    if ($row["status_order"] == 1) {
        $status_order = 'Принят';
        $color = '#28a745';
        $dsp_toggle = 'none';

    }elseif ($row["status_order"] == 0) {
        $status_order = 'Черновик';
        $color = 'silver';
    }elseif ($row["status_order"] == 2) {
        $status_order = 'Удален';
        $color = '#dc3545';
    }




?> 
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $supplier = get_supplier($connect, $row["supplier_id"]);?>&nbsp;<?php echo $supplier["name"];?></td>
            <td><?php echo number_format(floatval($row['sum_order']), 0, '.', ' '); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["date_order"])); ?></td>
            <td><span style="display:flex; justify-content:center; width:50%; border: 1px solid; background-color: <?php echo $color;?>; padding: 5px 10px; border-radius: 4px; color: white;"><?php echo $status_order; ?></span></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow">
                <div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <a href="view_prod-sklad.php?id=<?php echo $row["id"]; ?>&&date=<?php echo $row["date_order"]; ?>&&sum=<?php echo $row["sum_order"]; ?>&&note=<?php echo $row["order_note"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="edit_pro-sklad.php?id=<?php echo $row["id"]; ?>&&date=<?php echo $row["date_order"]; ?>&&sum=<?php echo $row["sum_order"]; ?>&&note=<?php echo $row["order_note"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="action-sklad.php?store_id=<?=$row['id']?>&&supplier_id=<?=$row['supplier_id']?>&&credit=<?=$row['sum_order']?>&&ord_date=<?=$row['date_order']?>&&payment_type=<?=$row['payment_type']?>"><button onclick="return confirm('Принят?')" class="btn btn-custom">Принят</button> </a>
                    <a style="display:<?php if($row["status_order"] == 1){echo 'true';}else {echo 'none';}?>;" href="action-sklad.php?draft_store_id=<?=$row['id']?>&&supplier_id=<?=$row['supplier_id']?>&&credit=<?=$row['sum_order']?>&&ord_date=<?=$row['date_order']?>&&payment_type=<?=$row['payment_type']?>"><button onclick="return confirm('Черновик?')" class="btn btn-custom">Черновик</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="action-sklad.php?cencel_id_store=<?=$row['id']?>"><button onclick="return confirm('Отменить?')" class="btn btn-custom">Отменить</button> </a>
                    <!-- <a style="display:<?php if($row["status_order"] == 0 OR $row["status_order"] == 1){echo 'true';}else {echo 'none';}?>;" href="#" class="btn btn-custom">Счет-фактура</button> </a> -->
                </div> 
            </td>
        </tr>
        
<?php       
    };    
?>      
        </tbody>
        </table>
        <table class="table" style="background-color:#ebf0ff; border-left: 4px solid #7396ff;">
            <tr>
                <td style="text-align:left;">Кол-во прихода: <?php echo number_format($all_count, 0, ',', ' '); ?>
                <span style="margin-left:100px;">Сумма прихода: <?php echo number_format($all_debt, 0, ',', ' '); ?></span>
                
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
                            while ($option_supplier = mysqli_fetch_array($supplier_tbl)) {    
                        ?>
                            <option value="<?php echo $option_supplier["id"];?>"><?php echo $option_supplier["name"]?></option>
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
    $('.accordian-body').on('show.bs.collapse', function () {
    $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        // .collapse('toggle')
})



$('.normalize').selectize();

$(document).ready(function () {
    $(document).on('click', '#loadBtn', function () {
        
      var row = Number($('#row').val());
      var count = Number($('#postCount').val());
      var limit = 15;
      row = row + limit;
    
      $('#row').val(row);
      $("#loadBtn").val('Loading...');
 
      $.ajax({
        type: 'POST',
        url: 'loadmore-data-sklad.php',
        data: 'rowstore=' + row +  '&i=' + <?php echo $i;?>,
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

<script src="js/snipping.js"></script>
</body>
</html>
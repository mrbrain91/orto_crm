<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}





//---------------------- add new order -----------------------------------------------

// yangi orderlarni nolga aylantirib olamiz. 
clear_count_new_rest($connect, 'count_new_order');

// yangi orderlarni sanab oladi
$get_new_order_item_count = get_new_order_item_count($connect);


// rest tabledagi yangi orderlarni zapis qilamiz xar zagruzka bolganida
while($row_store = mysqli_fetch_array($get_new_order_item_count)){
    upd_rest_count_new($connect, $row_store['prod_name'], $row_store['order_count']);
}

//---------------------- end ------------------------------------------------------------












//---------------------- add archive and delivered order -------------------------------------

// archive va deliveredlarni nolga aylantirib olamiz. 
clear_count_delivered_archive_rest($connect, 'count_archived_order');

// arxiv va dostavlenolarni sanaydi
$get_order_item_count = get_order_item_count($connect);

// update rest table 
while($row_store = mysqli_fetch_array($get_order_item_count)){
    upd_rest_count_archived($connect, $row_store['prod_name'], $row_store['order_count']);
}

//---------------------- end -------------------------------------------------------------------






//---------------------- add count_store order -----------------------------------------------


// count_store yani rest tabledagi columnni nolga aylantirib olamiz. 
clear_count_store_rest($connect, 'count_store');

// prinyat bugan skladga kirgan tovarni sanaydi 
$get_store_item_count = get_store_item_count($connect);
// update rest table 
while($row_store = mysqli_fetch_array($get_store_item_count)){
    upd_rest_count_store($connect, $row_store['prod_name'], $row_store['store_count']);
}

//---------------------- end -----------------------------------------------------------------------------







//---------------------- add return order -----------------------------------------------

// count_returned_order yani rest tabledagi columnni nolga aylantirib olamiz. 
clear_count_return_rest($connect, 'count_returned_order');

// count of returned items
$get_returned_item_count = get_returned_item_count($connect);
// update rest table 
while($row_returned = mysqli_fetch_array($get_returned_item_count)){
    upd_rest_count_return($connect, $row_returned['prod_name'], $row_returned['returned_count']);
}
//---------------------- end -----------------------------------------------------------------------------







// get fot list
$query = "SELECT * FROM rest_tbl ORDER BY id desc";
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
        <span class="right_cus"> Остатки на: <?php echo date("d.m.Y"); ?> </span>
    </div>    
</div>


<div class="all_table">
    <div class="container-fluid">
        <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">№</th>
                <th scope="col">Продукция</th>
                <th scope="col">Остаток</th>
                <th scope="col">Бронь</th>
                <th scope="col">Доступно</th>
            </tr>
        </thead>
        <tbody>
            
<?php     

    $i = 0;
    while ($row = mysqli_fetch_array($rs_result)) {
    $i++;
    $name = get_prod_name($connect, $row["prod_id"]);
    

       
?> 
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $name['name']; ?></td>
                <td><?php echo $ostatok = $row['count_store'] - $row['count_archived_order'] + $row['count_returned_order']; ?></td>
                <td><?php echo $row['count_new_order']; ?></td>
                <td><?php echo $dostupno = $ostatok - $row['count_new_order']; ?></td>
            </tr>
<?php 
    };
?>
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
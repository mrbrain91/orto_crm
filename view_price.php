<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

if (isset($_GET['id'])) {
   $id = $_GET['id'];

   $query = "SELECT * FROM price_item_tbl WHERE price_id='$id' ORDER by id DESC";  
   $rs_result = mysqli_query ($connect, $query);  

   $sql = "SELECT * FROM products_tbl";  
   $product_list = mysqli_query ($connect, $sql);

}


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
        <span class="right_cus">Просмотр цена</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
           <a href="price.php"> <button type="button" class="btn btn-custom">Закрыть</button> </a>
        </div>
</div>


<div>
    <div class="container-fluid">
        <table class="table table-hover">
        <thead>
            <tr>
            <th scope="col">№</th>
            <th scope="col">Название</th>
            <th scope="col">Цена</th>
            </tr>
        </thead>
        <tbody>
<?php     
    $i = 0; 
    while ($row = mysqli_fetch_array($rs_result)) {    
    $i++;  
?> 
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php $name = get_prod_name($connect, $row['name']); echo $name['name']; ?></td>
                <td><?php echo number_format($row["cost"], 0, ',', ' '); ?></td>
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
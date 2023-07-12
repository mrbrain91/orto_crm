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
$display_toggle = 'none';


if(isset($_POST['submit']) && $_POST['submit'] == 'Добавить') {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];


    $sql = "SELECT * FROM price_item_tbl WHERE price_id = '$id' AND name = '$product_name'";
    $result = mysqli_query ($connect, $sql);
    $existingRecord = mysqli_fetch_assoc($result);

    // Record already exists, show alert
    if ($existingRecord) { 
        $display_toggle = 'block';
    } else {
        add_product_price($connect, $id, $product_price, $product_name);
    }
}


?>


<!DOCTYPE html>
<html lang="en">
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
        <span class="right_cus">Редактировать цена</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <div class="toolbar_wrapper">
                <div>
                    <a href="price.php"> <button type="button" class="btn btn-custom">Закрыть</button> </a>
                </div>
                <div style='display:<?php echo $display_toggle; ?>; margin:0px;' class="alert alert-danger">
                    <strong style="margin-right: 22px;">Указанная запись уже существует!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
</div>


<div>
    <div class="container-fluid"> 
        <div class="card_head card_head_mt0">
        <form action=""  method="POST" class="horizntal-form" id="order_form">
            <div class="row mt">
                <div class="col-md-4">
                    <span>Продукция</span>
                </div>
                <div class="col-md-2">
                    <span>Цена</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <select required name="product_name" form="order_form" class="normalize">
                        <option value="">--выберитe---</option>
                        <?php     
                            while ($option = mysqli_fetch_array($product_list)) {    
                        ?> 
                            <option value="<?php echo $option["id"];?>"><?php echo $option["name"];?></option>
                        <?php       
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input required name="product_price" type="text" class="form-control" form="order_form">
                </div>
                <div class="col-md-3">
                    <input class="btn btn-success" type="submit" form="order_form" name="submit" value="Добавить" />
                </div>

            </div>
            
        </form></br>
        </div>




        
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
             <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
                <td><?php echo $i; ?></td>
                <td><?php $name = get_prod_name($connect, $row['name']); echo $name['name']; ?></td>
                <td><?php echo number_format($row["cost"], 0, ',', ' '); ?></td>
                
            </tr>
            <tr>
                <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <a href="action.php?del_pr_id=<?php echo $row["id"]; ?>&&pr_id=<?php echo $id ?>" onclick="return confirm('Удалить?')" role="button"><button class="btn btn-custom">Удалить</button></a>
                </td>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>

<script type ="text/javascript">
    $('.normalize').selectize();
</script>

<script src="js/snipping.js"></script>
</body>
</html>
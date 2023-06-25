 <?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$query = "SELECT * FROM price_tbl ORDER by id DESC LIMIT 1";  
$rs_result = mysqli_query ($connect, $query);

?>


<!DOCTYPE html>

<html lang="en">
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

<?php include 'partSite/nav.php'; ?>

<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus">Цена</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
           <a href="action.php?create_new_price='new'"><button onclick="return confirm('Создать?')" type="button" class="btn btn-success">Создать новый</button> </a>
        </div>
</div>


<div class="all_table">
    <div class="container-fluid">
        <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Ид</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Статус</th>
            </tr>
        </thead>
        <tbody>
<?php     
    
    while ($row = mysqli_fetch_array($rs_result)) {    

?> 
        
            <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">    
            
                <td>Прайс № <?php echo $row["id"]; ?></td>
                <td><?php echo $date = date("d.m.Y", strtotime($row["date"])); ?></td>
                <td>Активный</td>
            </tr>
            <tr>
                <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <a href="view_price.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                    <a href="edit_price.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
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


</body>
</html>
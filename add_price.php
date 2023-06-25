<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$last_id = get_id_new_ord($connect);


if(isset($_POST['submit']) && $_POST['submit'] == 'Сохранить') {
    add_each_ord($connect);
    $summ_prod = get_sum_main_ord($connect);
    add_main_prod($connect, $summ_prod);

}



$a = 'Пластырь One Aid PVC 19x72 №500';
$b = 'Пластырь One Aid PVC 19x72 №100';
$c = 'Пластырь One Aid PVC 19x72 №8';
$d = 'Пластырь One Aid PVC 25x72 №7';
$e = 'Пластырь One Aid PVC 38x72 №5';
$f = 'Пластырь One Aid PVC MIX №12';
$g = 'Пластырь One Aid PU 19x72 №5';
$h = 'Пластырь One Aid PU 38x72 №3';
$i = 'Пластырь One Aid PU MIX №9';
$j = 'Пластырь One Aid PU 60x70 №3';



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
    <link rel="stylesheet" href="css/style.css">

    <title>ortosavdo</title>
    
</head>
<body>  

<?php include 'partSite/nav.php'; ?>


<div class="page_name">
    <div class="container-fluid">
        <i class="fa fa-clone" aria-hidden="true"></i>
        <i class="fa fa-angle-double-right right_cus"></i>
        <span class="right_cus"> Добавление цена</span>
    </div>    
</div>

<div class="toolbar">
        <div class="container-fluid">
            <!-- <button type="button" class="btn btn-primary">Сохранить</button> -->
            <!-- <button type="button" class="btn btn-success">Принять</button> -->
            <td><input class="btn btn-success" type="submit" form="order_form" name="submit" value="Сохранить" />
            <a href="price.php"><button type="button" class="btn btn-custom">Закрыть</button></a>

        </div>
</div>

<div class="card_head">
    <div class="container-fluid">
        <form action="#"  method="POST" class="horizntal-form" id="order_form">
            <div class="row">
                <div class="col-md-3">
                    <span>Дата</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input type="date" value="<?php echo date("Y-m-d"); ?>"  class="form-control" name="main_order_date" form="order_form">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="add_pro_lab">
    <div class="container-fluid">
        Продукции 

    </div>
    <!-- <button type="button" class="btn btn-primary" id="addrow" >добавить строку</button>a -->

</div>

<div class="container-fluid">
<div class="row">
    <table id="orders" class="table order-list">
    <thead>
        <tr>
            <td>Продукция </td>
            <td>Цена</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-sm-4">
                <select required name="prod_name[]" form="order_form" class="form-control" onchange="showCustomer(this.value)">
                    <option value="">--выберите продукцию---</option>
                    <option value="<?php echo $a;?>"><?php echo $a;?></option>
                    <option value="<?php echo $b;?>"><?php echo $b;?></option>
                    <option value="<?php echo $c;?>"><?php echo $c;?></option>
                    <option value="<?php echo $d;?>"><?php echo $d;?></option>
                    <option value="<?php echo $e;?>"><?php echo $e;?></option>
                    <option value="<?php echo $f;?>"><?php echo $f;?></option>
                    <option value="<?php echo $g;?>"><?php echo $g;?></option>
                    <option value="<?php echo $h;?>"><?php echo $h;?></option>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <option value="<?php echo $j;?>"><?php echo $j;?></option>
                </select>
            </td>
            <td class="col-sm-1">
                <div id="txtHint">
                    <input data-type="product_price" type="number" name="product_price[]" id='product_price_1'  class="form-control product_price" for="1" form="order_form"/">
                </div>
            </td>
            <td class="col-sm-1">
                <!-- <i class="fa fa-plus"  id="addrow" style="cursor:pointer"></i> -->
                <td><button type="button" name="add" id="add" class="btn btn-success circle">+</button></td>

            </td>
            
            <td class="col-sm-1"><a class="deleteRow"></a>   

            </td>
        </tr>
    </tbody>
    <tfooter>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Количество: 0</td>
            <td>Сумма: 0</td>
        </tr>
    </tfooter>

</table>
<input class="form-control" type='hidden' data-type="product_id_1" id='product_id_1' name='product_id[]'/>            

</div>
</div>


<div class="line line-dashed line-lg pull-in" style="clear: both;"></div>
        
        <div class="col-md-12 nopadding">
          <div class="col-md-4 col-md-offset-4 pull-right nopadding">
            <div class="col-md-8 pull-right nopadding">
              <div class="form-group">
                <td><input class="form-control subtotal" type='text' id='subtotal' name='subtotal' readonly/></td>
              </div>
            </div>
            <div class="col-md-3 pull-right">
              <div class="form-group">
                <label>Subtotal</label>
              </div>
            </div>
          </div>
        </div>



<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>



</body>

<script>




// -------------------------------------------- select bazadan olish-------------------------------------------------------


function showCustomer(str) {
  var xhttp;    
  if (str == "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "getcustomer.php?q="+str, true);
  xhttp.send();
}

// ----------------------------------------------input calculate------------------------------------------------------------------------------

var rowCount = 1;
  
  $('#add').click(function() {
    rowCount++;
    $('#orders').append('<tr id="row'+rowCount+'"><td class="col-sm-4"><select required name="prod_name[]" form="order_form" class="form-control" onchange="showCustomer(this.value)"><option value="">--выберите продукцию---</option><option value="<?php echo $a;?>"><?php echo $a;?></option><option value="<?php echo $b;?>"><?php echo $b;?></option><option value="<?php echo $c;?>"><?php echo $c;?></option><option value="<?php echo $d;?>"><?php echo $d;?></option><option value="<?php echo $e;?>"><?php echo $e;?></option><option value="<?php echo $f;?>"><?php echo $f;?></option><option value="<?php echo $g;?>"><?php echo $g;?></option><option value="<?php echo $h;?>"><?php echo $h;?></option><option value="<?php echo $i;?>"><?php echo $i;?></option><option value="<?php echo $j;?>"><?php echo $j;?></option></select></td><td class="col-sm-1"><input class="form-control quantity" required type="number" class="form-control quantity" id="quantity_'+rowCount+'" name="quantity[]" for="'+rowCount+'" form="order_form"/> </td><td class="col-sm-1"><div id="txtHint"><input data-type="product_price" class="form-control product_price" type="number" data-type="product_price" id="product_price_'+rowCount+'" name="product_price[]" for="'+rowCount+'"/></div></td><input class="form-control" type="hidden" data-type="product_id" id="product_id_'+rowCount+'" name="product_id[]" for="'+rowCount+'"/><td><input class="form-control total_cost" type="text" id="total_cost_'+rowCount+'" name="total_cost[]"  for="'+rowCount+'" readonly/> </td><td><button type="button" name="remove" id="'+rowCount+'" class="btn btn-danger btn_remove cicle">-</button></td></tr>');
});

// Add a generic event listener for any change on quantity or price classed inputs
$("#orders").on('input', 'input.quantity,input.sale,input.product_price', function() {
  getTotalCost($(this).attr("for"));
});

$(document).on('click', '.btn_remove', function() {
  var button_id = $(this).attr('id');
  $('#row'+button_id+'').remove();
});

// Using a new index rather than your global variable i
function getTotalCost(ind) {
  var qty = $('#quantity_'+ind).val();
  var sale = $('#sale_'+ind).val();
  var price = $('#product_price_'+ind).val();
  var totNumber = (qty * price)+(qty * price*sale)/100;


  var tot = totNumber.toFixed(2);
  $('#total_cost_'+ind).val(tot);
  calculateSubTotal();
}

function calculateSubTotal() {
  var subtotal = 0;
  $('.total_cost').each(function() {
     subtotal += parseFloat($(this).val());
  });
  $('#subtotal').val(subtotal);
}

// -----------------------------row qoshish-----------------------------------------------------------------------------------------------


$(document).ready(function () {
    var counter = 0;


 

    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";                                                      
                
                                                                                                                                                                 
        cols += '<td><select name="prod_name[]" form="order_form" class="form-control"><option value="">--выберите продукцию--</option><option value="<?php echo $a; ?>"><?php echo $a; ?></option><option value="<?php echo $b; ?>"><?php echo $b; ?></option><option value="<?php echo $c; ?>"><?php echo $c; ?></option><option value="<?php echo $d; ?>"><?php echo $d; ?></option><option value="<?php echo $e; ?>"><?php echo $e; ?></option><option value="<?php echo $f; ?>"><?php echo $f; ?></option><option value="<?php echo $g; ?>"><?php echo $g; ?></option><option value="<?php echo $h; ?>"><?php echo $h; ?></option><option value="<?php echo $i; ?>"><?php echo $i; ?></option><option value="<?php echo $j; ?>"><?php echo $j; ?></option></select></td>';
        cols += '<td><input type="text" name="price_name[]"  class="form-control" form="order_form"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });


});



// function calculateRow(row) {
//     var price = +row.find('input[name^="price"]').val();

// }

// function calculateGrandTotal() {
//     var grandTotal = 0;
//     $("table.order-list").find('input[name^="price"]').each(function () {
//         grandTotal += +$(this).val();
//     });
//     $("#grandtotal").text(grandTotal.toFixed(2));
// }
</script>
</html>
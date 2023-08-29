<?php

include('settings.php');
include('bot_lib.php');

if (!isset($_SESSION['usersname'])) {
  header("location: index.php");
}

$last_id = get_id_new_ord($connect);


//get  users
$sql = "SELECT * FROM users_tbl WHERE role='sale'";
$users_tbl = mysqli_query ($connect, $sql);
//end get users 


//get counterparties
$sql = "SELECT * FROM counterparties_tbl ORDER BY name ASC";
$counterparties_tbl = mysqli_query ($connect, $sql);
//end get counterparties 



//get product from price 
$sql = "SELECT * FROM price_item_tbl WHERE price_id=(SELECT max(id) FROM price_tbl)";  
$product_list = mysqli_query ($connect, $sql);
//end get product

$display_toggle = 'none';


if(isset($_POST['submit']) && $_POST['submit'] == 'Принять') {

    $prod_names = $_POST['prod_name'];
    $unique_prod_names = array_unique($prod_names);
    

    if (count($prod_names) != count($unique_prod_names)) {
        $display_toggle = 'block';
    }else {

        // Call the function to add each order
        add_each_ord($connect);

        $main_order_contractor = $_POST['main_order_contractor'];
        $main_order_sale_agent = $_POST['main_order_sale_agent'];
        $main_order_date = $_POST['main_order_date'];
        $main_order_deliver_date = $_POST['main_order_deliver_date'];
        $main_order_paymen_type = $_POST['main_order_paymen_type'];
        $total_name = get_sum_main_ord($connect);


        // Call the function to add the main order
        add_main_ord($connect, $main_order_contractor, $main_order_sale_agent, $main_order_date, $main_order_deliver_date, $main_order_paymen_type, $total_name);
       
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
    <!-- bootstrap-select css2-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap-grid.min.css">
    <!--selectize css-->
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
        <span class="right_cus"> Добавление сделки</span>
    </div>    
</div>

<div class="toolbar">
    <div class="container-fluid">
        <div class="toolbar_wrapper">
            <div>
                <td><input data-toggle="modal" data-target="#exampleModal1" class="btn btn-success" type="submit" value="Принять" />
                <a href="order.php"><button type="button" class="btn btn-custom">Закрыть</button></a>
            </div>
            <div style='display:<?php echo $display_toggle; ?>; margin:0px;' class="alert alert-danger">
                <strong style="margin-right: 22px;">Ошибка: Названия продуктов повторяются.!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="card_head">
    <div class="container-fluid">
        <form action="#"  method="POST" class="horizntal-form" id="order_form">

            <div class="row">
                <div class="col-md-3">
                    <span>Дата сделки</span>
                </div>
                <div class="col-md-3">
                    <span>Дата отгрузки</span>
                </div>
                <div class="col-md-3">
                    <span>Условия оплаты</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <!-- <input type="date" value="<?php echo date("Y-m-d"); ?>"  class="form-control" name="main_order_date" form="order_form"> -->
                    <input type="date" value="<?php echo date("Y-m-d"); ?>" class="form-control" name="main_order_date" form="order_form" id="main_order_date_input">

                </div>
                <div class="col-md-3">
                    <input type="date" value="<?php echo date("Y-m-d"); ?>"  class="form-control" name="main_order_deliver_date" form="order_form">
                </div>
                <div class="col-md-3">
                    <select required name="main_order_paymen_type" form="order_form" class="normalize">
                            <option value="">--выберите---</option>
                            <option value="<?php echo 'Перечисление';?>"><?php echo 'Перечисление';?></option>
                            <option value="<?php echo 'Наличные деньги';?>"><?php echo 'Наличные деньги';?></option>
                    </select>
                </div>
            </div>
            <div class="row mt">
                <div class="col-md-3">
                    <span>Торговый представитель</span>
                </div>
                <div class="col-md-3">
                    <span>Контрагент</span>
                </div>
                <div class="col-md-3">
                    <span>Баланс контрагента</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <select required name="main_order_sale_agent" form="order_form" class="normalize">
                        <option value="">--выберитe---</option>
                        <?php    
                            while ($option = mysqli_fetch_array($users_tbl)) {    
                        ?>
                            <option value="<?php echo $option["id"];?>"><?php echo $option["surname"]?> <?php echo $option["name"];?> <?php echo $option["fathername"];?></option>
                        <?php       
                            };    
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select required name="main_order_contractor" form="order_form" class="normalize" onchange="showCustomerBalance(this.value)">
                        <option value="">--выберитe---</option>
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
                    <div id="balance">
                        <input disabled type="text" class="form-control" value="">
                    </div>
                </div>
            </div>      
        </form>
    </div>
</div>

<div class="prod_list">
    <div class="container-fluid">
        <table id="orders" class="table order-list">
            <thead>
                <tr>
                    <td>Продукция  / Производитель </td>
                    <td>Количество</td>
                    <!-- <td>Срок годности</td> -->
                    <td>Базовая цена</td>
                    <td style="width:9%;">
                        Скидка:  

                        <input form="order_form"  type="radio" id="ChoicePercent" name="saletype" value="ChoicePercent" checked />
                        <label for="ChoicePercent">%</label>
                        
                        <input form="order_form" type="radio" id="ChoiseSum" name="saletype" value="ChoiseSum"  />
                        <label for="ChoiseSum">$</label>

                    </td>
                    <td>Переоценка</td>
                    <td>Сумма к оплате</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-sm-4">
                        <select required name="prod_name[]" form="order_form" class="normalize" id='prod_name_1' for='1' onchange="showCustomer(this.value,'1')">
                            <option value="" class="form-control" >--выберитe продукцию---</option>
                            <?php     
                                while ($option = mysqli_fetch_array($product_list)) {    
                            ?> 
                                <option value="<?php echo $option["name"];?>"><?php $name = get_prod_name($connect, $option['name']); echo $name['name'];?></option>

                            <?php       
                                };    
                            ?>
                        </select>
                    </td>
                    <td class="col-sm-1">
                        <input required type="number" name="quantity[]" min="1"  class="form-control quantity" id='quantity_1' for='1' form="order_form"/>
                    </td>
                    <td class="col-sm-1">
                        <div id="txtHint_1">
                            <input disabled data-type="product_price" type="number" name="product_price[]" id='product_price_1'  class="form-control product_price" for="1" form="order_form"/">
                        </div>
                    </td>
                    <td class="col-sm-1">
                        <input required name="sale[]" type="number" placeholder="0" max="0" value="0" class="form-control sale" id='sale_1' for='1' form="order_form"/>        
                    </td>
                    <td class="col-sm-1">
                        <input disabled type="text" name="after_sale[]" id='after_sale_1' value="0"  class="form-control product_price" for="1" form="order_form"/">
                    </td>
                        <input  type="hidden" name="sale_type[]" id='sale_type_1' value="sum"  class="form-control product_price" for="1" form="order_form"/">
                    <td class="col-sm-2">
                        <input readonly type="text" name="total_cost[] "  class="form-control total_cost" id='total_cost_1' for='1' form="order_form"/>
                    </td>    
                    <td class="col-sm-1">
                    <button type="button" name="addrow" id="addrow" class="btn btn-success circle">+</button>
                    </td>
                </tr>
            </tbody>
            <tfooter>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <span style="line-height: 30px; float: right;"">
                        Общая сумма:
                        </span>
                    </td>
                    <td>
                    <input class="form-control subtotal" type='text' id='subtotal' name='subtotal' readonly/></td>

                    <td>
                    </td>
                </tr>
            </tfooter>
        </table>
        <input class="form-control" type='hidden' data-type="product_id_1" id='product_id_1' name='product_id[]'/>
    </div> 
</div>




<div class="line line-dashed line-lg pull-in" style="clear: both;"></div>
        


<div class="container-fluid">

    <?php include 'partSite/modal.php'; ?>
    
</div>


<!-- JavaScript links -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"></script>

<!--bootstrap-select js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script src="js/snipping.js"></script>
</body>

<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('main_order_date_input').focus();
});
</script>


<script>

$('.normalize').selectize();


//script selectpicker
$(function() {
  $('.selectpicker').selectpicker();
});

// function formatBYUnber thousand separator
function formatNumber(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}


// -------------------------------------------- select bazadan olish-------------------------------------------------------

function showCustomer(str, inc) {
  var xhttp;    
  if (str == "") {
    document.getElementById("txtHint_"+inc).innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("txtHint_"+inc).innerHTML = this.responseText;

    }
  };
  xhttp.open("GET", "getcustomer.php?q="+str+"&&i="+inc+"", true);
  xhttp.send();
}

// ----------------------------------------------input calculate------------------------------------------------------------------------------



$(document).ready(function() {
    $('input[type=radio][id=ChoicePercent]').change(function() {
        console.log('percent');
        $("#orders").on('input', 'input.quantity,input.sale,input.product_price', function() {
            getTotalCost($(this).attr("for"));
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr('id');
            $('#row'+button_id+'').remove();
        });

      
        function getTotalCost(ind) {
            var qty = $('#quantity_'+ind).val();
            var sale = $('#sale_'+ind).val();
            var price = $('#product_price_'+ind).val();

            var totNumber = (qty * price)+(qty * price*sale)/100;
            var afterSale = price - ((-price*sale)/100);
            $('#after_sale_'+ind).val(afterSale);
            $('#sale_type_'+ind).val('percent');
            $('#total_cost_'+ind).val(formatNumber(totNumber));
            calculateSubTotal();
        }

        function calculateSubTotal() {
        var subtotal = 0;
        $('.total_cost').each(function() {
            var value = $(this).val().replace(/\s/g, ''); // Remove thousand separators
            subtotal += parseFloat(value);
        });

        $('#subtotal').val(formatNumber(subtotal));
        }

    });

    $('input[type=radio][id=ChoiseSum]').change(function() {
        console.log('sum');
        $("#orders").on('input', 'input.quantity,input.sale,input.product_price', function() {
            getTotalCost($(this).attr("for"));
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr('id');
            $('#row'+button_id+'').remove();
        });

      
        function getTotalCost(ind) {
            var qty = $('#quantity_'+ind).val();
            var sale = $('#sale_'+ind).val();
            var price = $('#product_price_'+ind).val();

            var totNumber = (price - (-sale))*qty;
            afterSale = price - (-sale);
            $('#after_sale_'+ind).val(afterSale);
            $('#sale_type_'+ind).val('sum');
            $('#total_cost_'+ind).val(formatNumber(totNumber));
            calculateSubTotal();
        }

        function calculateSubTotal() {
        var subtotal = 0;
        $('.total_cost').each(function() {
            var value = $(this).val().replace(/\s/g, ''); // Remove thousand separators
            subtotal += parseFloat(value);
        });

        $('#subtotal').val(formatNumber(subtotal));
}

    });

    // Check if no radio button is selected on page load
    if (!$('input[type=radio][name=myRadio]:checked').length) {

        $("#orders").on('input', 'input.quantity,input.sale,input.product_price', function() {
            getTotalCost($(this).attr("for"));
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr('id');
            $('#row'+button_id+'').remove();
        });

      
        function getTotalCost(ind) {
            var qty = $('#quantity_'+ind).val();
            var sale = $('#sale_'+ind).val();
            var price = $('#product_price_'+ind).val();

            var totNumber = (qty * price)+(qty * price*sale)/100;
            var afterSale = price - ((-price*sale)/100);
            $('#after_sale_'+ind).val(afterSale);
            $('#sale_type_'+ind).val('percent');
            $('#total_cost_'+ind).val(formatNumber(totNumber));
            calculateSubTotal();
        }

        function calculateSubTotal() {
        var subtotal = 0;
        $('.total_cost').each(function() {
            var value = $(this).val().replace(/\s/g, ''); // Remove thousand separators
            subtotal += parseFloat(value);
        });

        $('#subtotal').val(formatNumber(subtotal));
}

    }

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr('id');
        $('#row'+button_id+'').remove();
    });

}); // end document ready line









// -----------------------------row qoshish-----------------------------------------------------------------------------------------------


<?php
    //get product from price 
    $sql = "SELECT * FROM price_item_tbl WHERE price_id=(SELECT max(id) FROM price_tbl)";  
    $product_list = mysqli_query ($connect, $sql);
    //end get product
?>



$(document).ready(function () {
    var counter = 0;
    var inc = 1;
 
    $("#addrow").on("click", function () {
        inc++;
        var newRow = $("<tr>");
        var cols = "";                                                      
                
        cols += '<td><select required name="prod_name[]" form="order_form" id="prod_name_'+inc+'" for="'+inc+'" onchange="showCustomer(this.value,'+inc+')"><option value="">--выберите продукцию--</option><?php while ($option = mysqli_fetch_array($product_list)) { ?> <option value="<?php echo $option["name"];?>"><?php  $name = get_prod_name($connect, $option["name"]); echo $name["name"]; ?></option> <?php }; ?></select></td>';

        cols += '<td><input required type="number" name="quantity[]" min="1" class="form-control quantity" id="quantity_'+inc+'" for="'+inc+'" form="order_form"/></td>';
        cols += '<td><div id="txtHint_'+inc+'"><input disabled data-type="product_price" type="number" name="product_price[]"  class="form-control product_price" id="product_price_'+inc+'" for="'+inc+'" form="order_form"/></div></td>';
        cols += '<td><input required type="number" name="sale[]" value="0" class="form-control sale" id="sale_'+inc+'" for="'+inc+'" form="order_form"/></td>';
        cols += '<td><input disabled type="text" name="after_sale[]" id="after_sale_'+inc+'" value="0" class="form-control product_price" for="'+inc+'" form="order_form"/></td>';
        cols += '<input type="hidden" name="sale_type[]" id="sale_type_'+inc+'" value="sum" class="form-control product_price" for="'+inc+'" form="order_form"/>';
        

        cols += '<td><input readonly="" type="text" name="total_cost[] " class="form-control total_cost" id="total_cost_'+inc+'" for="'+inc+'" form="order_form"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="-"></td>';
        newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;

        $('#prod_name_'+inc+'').selectize();
        
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });

});



// -------------------------------------------- select bazadan olish-------------------------------------------------------

function showCustomerBalance(str) {
  var xhttp;    
  if (str == "") {
    document.getElementById("balance").innerHTML = "";
    // document.getElementById("sample").innerHTML = 'hi';
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("balance").innerHTML = this.responseText;

    }
  };
  xhttp.open("GET", "getcustomer.php?id_c="+str+"", true);
  xhttp.send();
}

$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});


</script>
</html>
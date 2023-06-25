<?php
$mysqli = new mysqli("localhost", "root", "root", "orto_db");
if($mysqli->connect_error) {
  exit('Could not connect');
}


//add order
if (isset($_GET['i'])) {
$i = $_GET['i'];

// echo $_GET['i'];
$sql = "SELECT cost FROM price_item_tbl WHERE name = ? AND price_id=(SELECT max(id) FROM price_tbl)"; 

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $_GET['q']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($pn);
$stmt->fetch();
$stmt->close();

?> 

<input readonly data-type="product_price" type="number" name="product_price[]"  class="form-control product_price" form="order_form"/ id="product_price_<?php echo $i;?>" value="<?php echo $pn;?>">

<?php
}

//add return
if (isset($_GET['i_r'])) {
  $i = $_GET['i_r'];
  
  // echo $_GET['i'];
  $sql = "SELECT cost FROM price_item_tbl WHERE name = ? AND price_id=(SELECT max(id) FROM price_tbl)"; 
  
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $_GET['q']);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($pn);
  $stmt->fetch();
  $stmt->close();
  
  ?> 
  
  <input readonly data-type="product_price" type="number" name="product_price[]"  class="form-control product_price" form="return_form"/ id="product_price_<?php echo $i;?>" value="<?php echo $pn;?>">
  
  <?php
  }
  



//get_balance 

if (isset($_GET['id_c'])) {
  $i = $_GET['id_c'];
  
  // echo $_GET['i'];
  $sql = "SELECT ( sum(main_prepayment) - sum(debt)) as balance FROM debts WHERE id_counterpartie = '$i' GROUP BY id_counterpartie";

  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $_GET['q']);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($pn);
  $stmt->fetch();
  $stmt->close();
  if ($pn>=0) {
   $color = 'green';
   $plus = '+';
  }else {
    $color = 'red';
  }
  ?> 
  
  <input readonly style="color: <?php echo $color; ?>; background-color:<?php echo "#fafafb"; ?>" type="text" class="form-control" value="<?php if(isset($plus)){echo $plus; };?> <?php echo number_format($pn, 0, ',', ' ');?>">

  
  <?php
  }



  //get_balance supplier

if (isset($_GET['id_s'])) {
  $i = $_GET['id_s'];
  
  // echo $_GET['i'];
  $sql = "SELECT (sum(credit) - sum(debt)) as balance FROM supplier WHERE id_supplier = '$i' GROUP BY id_supplier";

  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $_GET['q']);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($pn);
  $stmt->fetch();
  $stmt->close();
  if ($pn>=0) {
   $color = 'green';
  }else {
    $color = 'red';
  }
  ?> 
  
  <input readonly style="color: <?php echo $color; ?>; background-color:<?php echo "#fafafb"; ?>" type="text" class="form-control" value="<?php echo number_format($pn, 0, ',', ' ');?>">

  
  <?php
  }






?>





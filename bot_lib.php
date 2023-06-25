<?php

/********************************************************
*  Function library

********************************************************/


function redirect($address){
	header("location: $address");
}

// function login
function login($connect){

	if (isset($_POST['submit_log'])) {
		$username = trim($_POST['username']);

		$username = crypt($username, 'PASSWORD_DEFAULT');


		$pass = trim($_POST['pass']);

		$query = "SELECT * FROM admin_user WHERE username='$username'";
		$result = mysqli_query($connect, $query);

		// $n = mysqli_num_rows($result);

		if (mysqli_num_rows($result) == 0) {
			$text = 'Неправильный логин или пароль';
		}
		else{
			
			$row = mysqli_fetch_assoc($result);

			if (crypt($pass, $row['password']) !== $row['password']) {
			
				$text = 'Неправильный логин или пароль';
				
			}
			else{
				$_SESSION['usersname'] = $username;

				if ($username === 'PA7/4ru0/NasI') {
					redirect('main-sklad.php');
				}else {
					redirect('main.php');
				}

			}
		}
		return $text;
	}
}

// function get state in
function get_status_in($connect, $id){
	
	$query = "SELECT * FROM state_in WHERE id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$state = mysqli_fetch_assoc($result);
	return $state;
}

// function get state out
function get_status_out($connect, $id){
	$query = "SELECT * FROM state_out WHERE id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$state = mysqli_fetch_assoc($result);
	// exit();
	return $state;
}

function add_state_in($connect, $name) {

	$sql = "INSERT INTO `state_in` (`name`) VALUES ('".$name."');";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_in.php");
	}
}

function add_state_out($connect, $name) {

	$sql = "INSERT INTO `state_out` (`name`) VALUES ('".$name."');";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_out.php");
	}
}

// function number to word
function str_price($value){
	$value = explode('.', number_format($value, 2, '.', ''));
 
	$f = new NumberFormatter('ru', NumberFormatter::SPELLOUT);
	$str = $f->format($value[0]);
 
	// Первую букву в верхний регистр.
	$str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));
	
	return $str;
}


//get store item count count for rest 
function get_store_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS store_count FROM order_item_product WHERE store_itm_sts='1' GROUP BY prod_name ORDER BY prod_name asc";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}

//get returned item count for rest 
function get_returned_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS returned_count FROM return_item_tbl WHERE return_itm_sts='0' GROUP BY prod_name ORDER BY prod_name asc";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}


//get_ortder_item count for rest 
function get_order_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS order_count FROM main_ord__item_tbl WHERE order_itm_sts='1' GROUP BY prod_name ORDER BY order_count DESC";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}

// clear count rest table column
function clear_count_rest($connect, $column_name) {
	$sql1 = "SELECT prod_id FROM rest_tbl";
	$res1 = mysqli_query($connect, $sql1);
	while($row1 = mysqli_fetch_array($res1)) {
		$prod_id = $row1['prod_id'];
		$sql = "UPDATE rest_tbl SET $column_name = '0' WHERE prod_id='$prod_id'";
		mysqli_query($connect, $sql);
	}
}

//get_ortder_item count for rest new bron order
function get_new_order_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS order_count FROM main_ord__item_tbl WHERE order_itm_sts='0' GROUP BY prod_name ORDER BY order_count DESC";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}


function del_main_ord_item_tbl($connect, $pi){

	$sql = "DELETE FROM main_ord__item_tbl WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function del_store_item_tbl($connect, $pi){

	$sql = "DELETE FROM order_item_product WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function del_return_item_tbl($connect, $pi){

	$sql = "DELETE FROM return_item_tbl WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_main_ord_item($connect, $orid, $pi, $p_name, $c_name, $pr__name, $s_name, $s_type, $t_name){

	$sql = "UPDATE main_ord__item_tbl 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	price_name = '$pr__name',
	sale_name = '$s_name',
	sale_type = '$s_type',
	total_name = '$t_name'
	WHERE id='$pi' AND order_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_store_item($connect, $orid, $pi, $p_name, $c_name, $pr_name, $d_name, $t_name){
	
	$sql = "UPDATE order_item_product 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	date_name = '$d_name',
	price_name = '$pr_name',
	total_name = '$t_name'
	WHERE id='$pi' AND order_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_rest_count_store($connect, $prod_id, $count_store){
	$sql = "UPDATE rest_tbl 
	SET 
	count_store = '$count_store'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_return($connect, $prod_id, $count_return){
	$sql = "UPDATE rest_tbl 
	SET 
	count_returned_order = '$count_return'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_archived($connect, $prod_id, $count_archive){
	$sql = "UPDATE rest_tbl 
	SET 
	count_archived_order = '$count_archive'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_new($connect, $prod_id, $count_new){
	$sql = "UPDATE rest_tbl 
	SET 
	count_new_order = '$count_new'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_return_item($connect, $orid, $pi, $p_name, $c_name, $pr__name, $s_name, $t_name){

	$sql = "UPDATE return_item_tbl 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	price_name = '$pr__name',
	sale_name = '$s_name',
	total_name = '$t_name'
	WHERE id='$pi' AND return_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function get_pi_last_count($connect, $pi){
	
	$sql = "SELECT count_name FROM  main_ord__item_tbl WHERE id = '$pi'";
	$result = mysqli_query($connect, $sql);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		return $rows[0];
}


function get_user($connect, $user_id){
	$query = "SELECT * FROM users_tbl WHERE id='$user_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$user = mysqli_fetch_assoc($result);
	return $user;
}

function get_contractor($connect, $contractor_id){
	$query = "SELECT * FROM counterparties_tbl WHERE id='$contractor_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$contractor = mysqli_fetch_assoc($result);
	return $contractor;
}

function get_prod_name($connect, $prod_id){
	$query = "SELECT * FROM products_tbl WHERE id='$prod_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$prod_name = mysqli_fetch_assoc($result);
	return $prod_name;
}

function get_supplier($connect, $supplier_id){
	$query = "SELECT * FROM supplier_tbl WHERE id='$supplier_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$supplier = mysqli_fetch_assoc($result);
	return $supplier;
}


function upd_prod_item($connect, $torid, $tpi, $tp_name, $tc_name, $td_name, $tpr__name, $ts_name, $tt_name){

	$sql = "UPDATE order_item_product 
	SET 
	prod_name = '$tp_name', 
	count_name = '$tc_name',
	date_name = '$td_name', 
	price_name = '$tpr__name',
	sale_name = '$ts_name',
	total_name = '$tt_name'
	WHERE id='$tpi' AND order_id='$torid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_sum($connect, $id, $sum){
	$sql = "UPDATE order_tbl
	SET 
	sum_order = '$sum'
	WHERE id='$id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_sts($connect, $archive_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '1'
	WHERE id='$archive_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto prixod prinyat qilganda, prixod statusini o'zgartirish
function upd_store_sts($connect, $store_id){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '1'
	WHERE id='$store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_sts_res($connect, $restore_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '1'
	WHERE id='$restore_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_order_sts_tonew($connect, $renew_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '0'
	WHERE id='$renew_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}



// prixod prinyat move to draft
function upd_store_sts_res($connect, $draft_store_id){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '0'
	WHERE id='$draft_store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_sts_del($connect, $delete_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '2'
	WHERE id='$delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_order_sts_close($connect, $closed_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '4'
	WHERE id='$closed_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_return_sts_del($connect, $return_delete_id){
	$sql = "UPDATE return_list
	SET 
	return_status = '1'
	WHERE id='$return_delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// prixod chernovek move to otmenen
function upd_store_sts_cencel($connect, $cencel_id_store){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '2'
	WHERE id='$cencel_id_store'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_itm_sts($connect, $archive_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '1'
	WHERE order_id='$archive_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto prixod item statuslarini printay qilish
function upd_store_itm_sts($connect, $store_id){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '1'
	WHERE order_id='$store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_itm_sts_res($connect, $restore_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '1'
	WHERE order_id='$restore_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_order_itm_sts_tonew($connect, $renew_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '0'
	WHERE order_id='$renew_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// prixod prinyat move to draft
function upd_store_itm_sts_res($connect, $draft_store_id){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '0'
	WHERE order_id='$draft_store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_order_itm_sts_del($connect, $delete_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '2'
	WHERE order_id='$delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_order_itm_sts_close($connect, $closed_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '4'
	WHERE order_id='$closed_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_return_itm_sts_del($connect, $return_delete_id){
	$sql = "UPDATE return_item_tbl
	SET 
	return_itm_sts = '1'
	WHERE return_id='$return_delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// prixod chernovek move to otmenen
function upd_store_itm_sts_cencel($connect, $cencel_id_store){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '2'
	WHERE order_id='$cencel_id_store'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_main_order_sum($connect, $orid, $sum){
	$sql = "UPDATE main_ord_tbl
	SET 
	transaction_amount = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// function update delivered date
function upd_main_order_deliver_date($connect, $orid, $delivery_date){
	$sql = "UPDATE main_ord_tbl
	SET 
	ord_deliver_date = '$delivery_date'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_store_sum($connect, $orid, $sum){
	$sql = "UPDATE order_tbl
	SET 
	sum_order = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_return_sum($connect, $orid, $sum){
	$sql = "UPDATE return_list
	SET 
	transaction_amount = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_return_debt_sum($connect, $orid, $sum){
	$sql = "UPDATE debts
	SET 
	main_prepayment = '$sum'
	WHERE return_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function get_id_new_order($connect){
	$query = "SELECT id FROM order_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}

function get_id_new_return($connect){
	$query = "SELECT id FROM return_list ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}


function get_id_new_ord($connect){
	$query = "SELECT id FROM main_ord_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}







function add_user($connect, $name, $surname, $fathername, $login, $pass, $role) {

	$sql = "INSERT INTO `users_tbl` (`name`, `surname`, `fathername`, `login`, `pass`, `role`) VALUES ('".$name."','".$surname."','".$fathername."','".$login."','".$pass."','".$role."');";
	if(mysqli_query($connect, $sql)) {
		redirect("users.php");
	}
	
}


function add_product_price($connect, $id, $product_price, $product_name){

	$sql = "INSERT INTO `price_item_tbl` (`price_id`, `name`, `cost`) VALUES ('".$id."','".$product_name."','".$product_price."');";
	if(mysqli_query($connect, $sql)) {
		redirect("edit_price.php?id=$id");
		return true;
	}
	else {
		die(mysqli_error($connect));
	}

}

// dostavshikka credit qoshish-prixod tovar orqali
function add_credit_supplier($connect, $supplier_id, $ord_date, $credit, $store_id, $set_sts){
	$sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `credit`, `come_id`, `status`) VALUES ('".$supplier_id."','".$ord_date."','".$credit."','".$store_id."','".$set_sts."');";	
	if (mysqli_query($connect, $sql)) {
		redirect("in_store.php");
	}else {
		die(mysqli_error($connect));
	}
}

// dostavshikka credit qoshish-prixod tovar orqali
function add_credit_supplier_sklad($connect, $supplier_id, $ord_date, $credit, $store_id, $set_sts){
	$sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `credit`, `come_id`, `status`) VALUES ('".$supplier_id."','".$ord_date."','".$credit."','".$store_id."','".$set_sts."');";	
	if (mysqli_query($connect, $sql)) {
		redirect("in_store-sklad.php");
	}else {
		die(mysqli_error($connect));
	}
}


function add_credit($connect, $archive_id, $contractor_id, $debt, $ord_date, $payment_type){

	$sql = "INSERT INTO `debts` (`order_id`, `id_counterpartie`, `debt`, `order_date`, `payment_type`) VALUES ('".$archive_id."','".$contractor_id."','".$debt."','".$ord_date."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {
		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$contractor_id'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `debt`) VALUES ('".$contractor_id."','".$debt."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET debt = debt + '$debt' WHERE id_counterpartie='$contractor_id'";
			mysqli_query($connect, $query);
		}

		redirect("order.php");
	}
	else {
		die(mysqli_error($connect));
	}

}

function add_debt($connect, $archive_id, $contractor_id, $debt, $ord_date, $payment_type){

	$sql = "INSERT INTO `debts` (`order_id`, `id_counterpartie`, `debt`, `order_date`, `payment_type`) VALUES ('".$archive_id."','".$contractor_id."','".$debt."','".$ord_date."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {
		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$contractor_id'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `debt`) VALUES ('".$contractor_id."','".$debt."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET debt = debt + '$debt' WHERE id_counterpartie='$contractor_id'";
			mysqli_query($connect, $query);
		}

		header("Location: order.php?message=Успешно доставлено заказ №_".$archive_id."");

	}
	else {
		die(mysqli_error($connect));
	}

}

function add_debt_sklad($connect, $archive_id, $contractor_id, $debt, $ord_date, $payment_type){

	$sql = "INSERT INTO `debts` (`order_id`, `id_counterpartie`, `debt`, `order_date`, `payment_type`) VALUES ('".$archive_id."','".$contractor_id."','".$debt."','".$ord_date."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {
		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$contractor_id'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `debt`) VALUES ('".$contractor_id."','".$debt."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET debt = debt + '$debt' WHERE id_counterpartie='$contractor_id'";
			mysqli_query($connect, $query);
		}

		header("Location: order-sklad.php?message=Успешно доставлено заказ №_".$archive_id."");

	}
	else {
		die(mysqli_error($connect));
	}

}


function delete_debt($connect, $renew_id){
	$sql = "DELETE FROM `debts` WHERE order_id IN ('$renew_id')";
	if(mysqli_query($connect, $sql)) {
		// redirect("archive_order.php");
		header("Location: order.php?message=Успешно изменен статус заказа №_".$restore_id."");

	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_debt_return($connect, $return_delete_id){
	$sql = "DELETE FROM `debts` WHERE return_id IN ('$return_delete_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("return_list.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_price_item($connect, $del_pr_id, $pr_id){
	$sql = "DELETE FROM `price_item_tbl` WHERE price_id = '$pr_id' AND id = '$del_pr_id'";

	if(mysqli_query($connect, $sql)) {
		redirect("edit_price.php?id=$pr_id");
	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_credit_supplier($connect, $draft_store_id){
	$sql = "DELETE FROM `supplier` WHERE come_id IN ('$draft_store_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("in_store.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_credit_supplier_sklad($connect, $draft_store_id){
	$sql = "DELETE FROM `supplier` WHERE come_id IN ('$draft_store_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("in_store-sklad.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function cash_in_add($connect, $state_id,  $cash_sum, $cash_type, $cash_comment, $cash_date){

	$sql = "INSERT INTO `cashbox` (`types_id`, `sum_in`, `type_payment`, `comment`, `date_cash`) VALUES ('".$state_id."','".$cash_sum."','".$cash_type."','".$cash_comment."','".$cash_date."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("cash_in.php");
	}

}

function cash_out_add($connect, $state_id,  $cash_sum, $cash_type, $cash_comment, $cash_date){

	$sql = "INSERT INTO `cashbox` (`types_id`, `sum_out`, `type_payment`, `comment`, `date_cash`) VALUES ('".$state_id."','".$cash_sum."','".$cash_type."','".$cash_comment."','".$cash_date."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("cash_out.php");
	}

}

function add_main_prepayment($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type, $sts){

	$sql = "INSERT INTO `debts` (`id_counterpartie`, `order_date`, `main_prepayment`, `payment_type`, `sts`) VALUES ('".$id_counterpartie."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."','".$sts."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("prepayment_list.php");
	}
}

function add_main_prepayment_return($connect, $id_counterpartie, $return_id, $prepayment_date, $prepayment_sum, $payment_type, $sts){
	$sql = "INSERT INTO `debts` (`id_counterpartie`, `return_id`, `order_date`, `main_prepayment`, `payment_type`, `sts`) VALUES ('".$id_counterpartie."','".$return_id."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."','".$sts."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("return_list.php");
	}

}


function add_debt_supplier($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type){

	$sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `debt`, `payment_type`) VALUES ('".$id_counterpartie."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("supplier_list.php");
	}

}


function add_product($connect, $name, $unit) {
	$sql = "INSERT INTO `products_tbl` (`name`, `unit`) VALUES ('".$name."','".$unit."');";
	if(mysqli_query($connect, $sql)) {
		//get last product id
		$query = "SELECT id FROM products_tbl ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($connect, $query);
		$rows = mysqli_fetch_row($result);
		$last_id = $rows[0];
		
		// set to rest table
		$sql_last = "INSERT INTO `rest_tbl` (`prod_id`) VALUES ('".$last_id."');";
			if (mysqli_query($connect, $sql_last)) {
				redirect("products.php");
			}	
	
	}
}	

function add_counterparties($connect, $name, $alternative_name, $inn, $nds, $raschetny_schet, $mfo, $address, $contact, $director, $accountant) {
	
	$sql = "INSERT INTO `counterparties_tbl` (`name`, `alternative_name`, `inn`, `nds`, `raschetny_schet`, `mfo`, `address`, `contact`, `director`, `accountant`) VALUES ('".$name."','".$alternative_name."','".$inn."','".$nds."','".$raschetny_schet."','".$mfo."','".$address."','".$contact."','".$director."','".$accountant."');";
	if(mysqli_query($connect, $sql)) {
		redirect("counterparties.php");
	}	
}

function edit_counterpartie($connect, $name, $alternative_name, $inn, $nds, $raschetny_schet, $mfo, $address, $contact, $director, $accountant, $id) {
	
	$sql = "UPDATE `counterparties_tbl` 
			SET
				name = '$name',
				alternative_name = '$alternative_name',
				inn = '$inn',
				nds = '$nds',
				raschetny_schet = '$raschetny_schet',
				mfo = '$mfo',
				address = '$address',
				contact = '$contact',
				director = '$director',
				accountant = '$accountant' 
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("counterparties.php");
	}	
}

function edit_product($connect, $name, $unit, $id) {
	
	$sql = "UPDATE `products_tbl` 
			SET
				name = '$name',
				unit = '$unit'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("products.php");
	}	
}

function edit_state_in($connect, $name, $id) {
	
	$sql = "UPDATE `state_in` 
			SET
				name = '$name'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_in.php");
	}	
}
function edit_state_out($connect, $name, $id) {
	
	$sql = "UPDATE `state_out` 
			SET
				name = '$name'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_out.php");
	}	
}

function edit_main_prepayment($connect, $id, $prepayment_date, $prepayment_sum, $payment_type) {
	
	$sql = "UPDATE `debts` 
			SET
			order_date = '$prepayment_date',
			main_prepayment = '$prepayment_sum',
			payment_type = '$payment_type'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("prepayment_list.php");
	}	
}
// function edit cash in
function edit_cash_in($connect, $id, $prepayment_date, $prepayment_sum, $payment_type, $cash_comment) {
	
	$sql = "UPDATE `cashbox` 
			SET
			date_cash = '$prepayment_date',
			sum_in = '$prepayment_sum',
			type_payment = '$payment_type',
			comment = '$cash_comment'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("cash_in.php");
	}	
}

// function edit cash out
function edit_cash_out($connect, $id, $prepayment_date, $prepayment_sum, $payment_type, $cash_comment) {
	
	$sql = "UPDATE `cashbox` 
			SET
			date_cash = '$prepayment_date',
			sum_out = '$prepayment_sum',
			type_payment = '$payment_type',
			comment = '$cash_comment'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("cash_out.php");
	}	
}

function edit_supplier_debt($connect, $id, $prepayment_date, $prepayment_sum, $payment_type) {
	
	$sql = "UPDATE `supplier` 
			SET
			order_date = '$prepayment_date',
			debt = '$prepayment_sum',
			payment_type = '$payment_type'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier_list.php");
	}	
}

function edit_user($connect, $name, $surname, $fathername, $login, $pass, $role, $id) {
	
	$sql = "UPDATE `users_tbl` 
			SET
				name = '$name',
				surname = '$surname',
				fathername = '$fathername',
				login = '$login',
				pass = '$pass',
				role = '$role'
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("users.php");
	}	
}

function edit_supplier($connect, $name, $alternative_name, $inn, $nds, $raschetny_schet, $mfo, $address, $contact, $director, $accountant, $id) {
	
	$sql = "UPDATE `supplier_tbl` 
			SET
				name = '$name',
				alternative_name = '$alternative_name',
				inn = '$inn',
				nds = '$nds',
				raschetny_schet = '$raschetny_schet',
				mfo = '$mfo',
				address = '$address',
				contact = '$contact',
				director = '$director',
				accountant = '$accountant' 
			WHERE id = '$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier.php");
	}	
}

function add_supplier($connect, $name, $alternative_name, $inn, $nds, $raschetny_schet, $mfo, $address, $contact, $director, $accountant) {

	$sql = "INSERT INTO `supplier_tbl` (`name`, `alternative_name`, `inn`, `nds`, `raschetny_schet`, `mfo`, `address`, `contact`, `director`, `accountant`) VALUES ('".$name."','".$alternative_name."','".$inn."','".$nds."','".$raschetny_schet."','".$mfo."','".$address."','".$contact."','".$director."','".$accountant."');";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier.php");
	}	
}

// edit page add product function
function edit_page_add($connect, $order_id, $prod_name, $count_name, $date_name, $price_name, $sale_name, $sale_type, $total_name) {

	$sql = "INSERT INTO `main_ord__item_tbl` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `sale_type`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$sale_type."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}


//store edit page  add product function
function edit_page_add_store($connect, $id, $prod_name, $count_name, $date_name, $price_name, $total_name) {

	$sql = "INSERT INTO `order_item_product` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `total_name`) VALUES ('".$id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}


// edit page return add product function
function edit_page_add_ret($connect, $order_id, $prod_name, $count_name, $date_name, $price_name, $sale_name, $total_name) {

	$sql = "INSERT INTO `return_item_tbl` (`return_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}

// add each order tbl
function add_each_ord($connect) {
	$query = "SELECT id FROM main_ord_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		$last_id = $rows[0];
	
	if(isset($_POST['submit']) && $_POST['submit'] == 'Принять'){

		
		foreach($_POST['prod_name'] as $row => $value){
			$sale_type = $_POST['sale_type'][$row];
			$prod_name=$_POST['prod_name'][$row];
			$count_name=$_POST['quantity'][$row];
			$date_name=$_POST['main_order_date'];                   
			$price_name=$_POST['product_price'][$row];
			$sale_name=$_POST['sale'][$row];
			if ($sale_type == 'sum') {
				$total_name = ($price_name - (- $sale_name)) * $count_name;

			} elseif ($sale_type == 'percent') {
				$total_name = ($count_name * $price_name) + ($count_name * $price_name * $sale_name) / 100;
			}
			$order_id = $last_id + 1;

			$sql = "INSERT INTO `main_ord__item_tbl` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `sale_type`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$sale_type."','".$total_name."');";
		
			if (mysqli_query($connect, $sql)) {
				echo 'successfully';
			}
			else {
				echo("Error description: " . $mysqli -> error);
			}
		}
	header("Location: order.php?message=Добавлен новый заказ №_".$order_id."");
	
	// exit();
}


}

// add each return tbl
function add_each_return($connect) {
	$query = "SELECT id FROM return_list ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		$last_id = $rows[0];
		
	
	if(isset($_POST['submit']) && $_POST['submit'] == 'Принять'){

		foreach($_POST['prod_name'] as $row => $value){

				$prod_name=$_POST['prod_name'][$row];
				$count_name=$_POST['quantity'][$row];
				$date_name=$_POST['return_date'];                   
				$price_name=$_POST['product_price'][$row];
				$sale_name=$_POST['sale'][$row];
				$total_name = ($count_name * $price_name) + ($count_name * $price_name * $sale_name) / 100;
				$return_id = $last_id + 1;

			$sql = "INSERT INTO `return_item_tbl` (`return_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$return_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";
			

			if (mysqli_query($connect, $sql)) {
				echo 'success';
			}
			else {
				echo("Error description: " . $mysqli -> error);
			}
		}
		redirect("return_list.php");
	}
}


function get_sum_id($connect, $id){

	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
}

//main_order
function get_sum_id_main($connect, $orid){

	$query = "SELECT SUM(total_name) FROM main_ord__item_tbl WHERE order_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
}

//return 
function get_sum_id_return($connect, $orid){

	$query = "SELECT SUM(total_name) FROM return_item_tbl WHERE return_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
}

//store edit
function get_sum_id_store($connect, $orid){

	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}


function get_sum($connect){

	$last_id = get_id_new_order($connect);
	$last_id = $last_id+1;

	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
}

function get_sum_main_ord($connect){

	$last_id = get_id_new_ord($connect);
	$last_id = $last_id+1;

	$query = "SELECT SUM(total_name) FROM main_ord__item_tbl WHERE order_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
}

function get_sum_return($connect){

	$last_id = get_id_new_return($connect);
	$last_id = $last_id+1;
	
	$query = "SELECT SUM(total_name) FROM return_item_tbl WHERE return_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];

}


//main_order
function sum_count_return($connect, $id){
	$query = "SELECT SUM(count_name) FROM return_item_tbl WHERE return_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
}


//main_order
function sum_count_main($connect, $id){

	$query = "SELECT SUM(count_name) FROM main_ord__item_tbl WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}

//store
function sum_count_store($connect, $id){

	$query = "SELECT SUM(count_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}

function sum_count($connect, $id){

	$query = "SELECT SUM(count_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
}



// orto 
function add_main_ord($connect, $main_order_contractor, $main_order_sale_agent, $main_order_date, $main_order_deliver_date, $main_order_paymen_type, $total_name) {
	
	$t = "INSERT INTO main_ord_tbl (contractor, sale_agent, ord_date, ord_deliver_date, payment_type, transaction_amount) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')";
	
	$query = sprintf($t, mysqli_real_escape_string($connect, $main_order_contractor),
						mysqli_real_escape_string($connect, $main_order_sale_agent),
						mysqli_real_escape_string($connect, $main_order_date),
						mysqli_real_escape_string($connect, $main_order_deliver_date),
						mysqli_real_escape_string($connect, $main_order_paymen_type),
						mysqli_real_escape_string($connect, $total_name));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto 
function add_return($connect, $return_contractor, $return_sale_agent, $return_date, $return_paymen_type, $total_name, $return_id) {
	
	$t = "INSERT INTO return_list (contractor, sale_agent, return_date, payment_type, transaction_amount) VALUES ('%s', '%s', '%s', '%s', '%s')";
	
	$query = sprintf($t, mysqli_real_escape_string($connect, $return_contractor),
						mysqli_real_escape_string($connect, $return_sale_agent),
						mysqli_real_escape_string($connect, $return_date),
						mysqli_real_escape_string($connect, $return_paymen_type),
						mysqli_real_escape_string($connect, $total_name));
    $result = mysqli_query($connect, $query);
	if($result){
		$sts = 3;
		add_main_prepayment_return($connect, $return_contractor, $return_id, $return_date, $total_name, $return_paymen_type, $sts);
		return true;
	}else{
		die(mysqli_error($connect));
	}
}

?>
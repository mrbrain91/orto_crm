<?php
include('settings.php');
include('bot_lib.php');


// order move to archive
if (isset($_GET['archive_id'])) {

    $archive_id = $_GET['archive_id'];
    $contractor = $_GET['contractor_id'];
    $debt = $_GET['debt'];
    $ord_date = $_GET['ord_deliver_date'];
    $payment_type = $_GET['payment_type'];
    if (upd_order_sts($connect, $archive_id)) {
       if (upd_order_itm_sts($connect, $archive_id)) {
            //--------------------debt yozish--------------------
            $sts = 0;
            add_debt($connect, $archive_id, $contractor, $debt, $ord_date, $payment_type, $sts);
        }
    }
}
//end order move to archive



// renew order move delivered to new
if (isset($_GET['renew_id'])) {
    $renew_id = $_GET['renew_id'];
    if (upd_order_sts_tonew($connect, $renew_id)) {
       if (upd_order_itm_sts_tonew($connect, $renew_id)) {
            delete_debt($connect, $renew_id);
		    header("Location: order.php?message=Успешно изменен статус заказа №_".$renew_id."");
       }
    }
}
// end 

// archive order move order.php
if (isset($_GET['restore_id'])) {
    $restore_id = $_GET['restore_id'];
    if (upd_order_sts_res($connect, $restore_id)) {
       if (upd_order_itm_sts_res($connect, $restore_id)) {
            // delete_debt($connect, $restore_id);
		header("Location: archive_order.php?message=Успешно восстановлен заказа №_".$restore_id."");
       }
    }
}
// end 

if (isset($_GET['restore_id']) && isset($_GET['sts'])) {
    $restore_id = $_GET['restore_id'];
    if (upd_order_sts_res($connect, $restore_id)) {
       if (upd_order_itm_sts_res($connect, $restore_id)) {
            header("Location: deleted_order.php"); 
       }
    }
    
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    if (upd_order_sts_del($connect, $delete_id)) {
       if (upd_order_itm_sts_del($connect, $delete_id)) {
            header("Location: order.php"); 
	header("Location: order.php?message=Успешно отменен заказ №_".$delete_id."");

       }
    }
    
}

if (isset($_GET['closed_id'])) {
    $closed_id = $_GET['closed_id'];
    if (upd_order_sts_close($connect, $closed_id)) {
       if (upd_order_itm_sts_close($connect, $closed_id)) {
	    header("Location: order.php?message=Успешно заархивирован заказ №_".$closed_id."");
       }
    }
    
}



// prixod sklad

// prixod draft move to prinyat
if (isset($_GET['store_id'])) {

    $store_id = $_GET['store_id'];
    $supplier_id = $_GET['supplier_id'];
    $credit = $_GET['credit'];
    $ord_date = $_GET['ord_date'];

    if (upd_store_sts($connect, $store_id)) {
       if (upd_store_itm_sts($connect, $store_id)) {
            //--------------------credit yozish--------------------
            $set_sts = 1;   
            // dostavshikka credit qoshish-prixod tovar orqali               
            add_credit_supplier($connect, $supplier_id, $ord_date, $credit, $store_id, $set_sts);
        }
    }
}
//end order move to archive


// prixod prinyat move to draft
if (isset($_GET['draft_store_id'])) {
    $draft_store_id = $_GET['draft_store_id'];
    //--------------------------------------++++------------------

    if (upd_store_sts_res($connect, $draft_store_id)) {
       if (upd_store_itm_sts_res($connect, $draft_store_id)) {
            delete_credit_supplier($connect, $draft_store_id);
       }
    }
    
}

// prixod draft move to otmenen
if (isset($_GET['cencel_id_store'])) {
    $cencel_id_store = $_GET['cencel_id_store'];
    
    if (upd_store_sts_cencel($connect, $cencel_id_store)) {
       if (upd_store_itm_sts_cencel($connect, $cencel_id_store)) {
            header("Location: in_store.php"); 
       }
    }
}

// end prixod sklad



// vozvrat tovara

if (isset($_GET['return_delete_id'])) {
    $return_delete_id = $_GET['return_delete_id'];

    if (upd_return_sts_del($connect, $return_delete_id)) {
       if (upd_return_itm_sts_del($connect, $return_delete_id)) {
            delete_debt_return($connect, $return_delete_id);
       }
    }
    
}

if (isset($_GET['del_pr_id'])) {
     $del_pr_id = $_GET['del_pr_id'];
    $pr_id = $_GET['pr_id'];
    delete_price_item($connect, $del_pr_id, $pr_id);
}

// end vozvrat tovara
    

//yangi prays yaratish
if (isset($_GET['create_new_price'])) {
    $date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `price_tbl` (`date`) VALUES ('$date')";
	if(mysqli_query($connect, $sql)) {
		redirect("price.php");
	}
    
}


//change status counterpartie
if (isset($_GET['change_sts_counterpartie_id'])) {
    $id = $_GET['change_sts_counterpartie_id'];
    $sql = "UPDATE counterparties_tbl SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("counterparties.php");
	}
}

//change status supplier
if (isset($_GET['change_sts_supplier_id'])) {
    $id = $_GET['change_sts_supplier_id'];
    $sql = "UPDATE supplier_tbl SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier.php");
	}
}

//change status product
if (isset($_GET['change_sts_product_id'])) {
    $id = $_GET['change_sts_product_id'];
    $sql = "UPDATE products_tbl SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("products.php");
	}
}

//change status user
if (isset($_GET['change_sts_user_id'])) {
    $id = $_GET['change_sts_user_id'];
    $sql = "UPDATE users_tbl SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("users.php");
	}
}

//change status type_cash_in
if (isset($_GET['change_sts_tci_id'])) {
    $id = $_GET['change_sts_tci_id'];
    $sql = "UPDATE state_in SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_in.php");
	}
}

//change status type_cash_out
if (isset($_GET['change_sts_tco_id'])) {
    $id = $_GET['change_sts_tco_id'];
    $sql = "UPDATE state_out SET sts = !sts WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_out.php");
	}
}

//change status type_cash_out
if (isset($_GET['change_del_prepayment_id'])) {
    $id = $_GET['change_del_prepayment_id'];
    $sql = "UPDATE debts SET del = !del WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("prepayment_list.php");
	}
}


//change status type_cash_out
if (isset($_GET['change_del_supplier_id'])) {
    $id = $_GET['change_del_supplier_id'];
    $sql = "UPDATE supplier SET del = !del WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier_list.php");
	}
}

//change status del cash_in
if (isset($_GET['change_del_cash_in_id'])) {
    $id = $_GET['change_del_cash_in_id'];
    $sql = "UPDATE cashbox SET del = !del WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("cash_in.php");
	}
}

//change status del cash_out
if (isset($_GET['change_del_cash_out_id'])) {
    $id = $_GET['change_del_cash_out_id'];
    $sql = "UPDATE cashbox SET del = !del WHERE id='$id'";
	if(mysqli_query($connect, $sql)) {
		redirect("cash_out.php");
	}
}













?>
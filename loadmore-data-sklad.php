<?php
include('settings.php');
include('bot_lib.php');


if (isset($_POST['row'])) {
  $start = $_POST['row'];
  $limit = 15;
  $query = "SELECT * FROM debts WHERE prepayment > '0' ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      ?>
        <tr class="item">
                <td><?php echo $row["order_id"]; ?></td>
                <td><?php $user = get_contractor($connect, $row["id_counterpartie"]); echo $user["name"];?></td>
                <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>

                <td><?php echo $row["payment_type"]; ?></td>
                <td><?php echo number_format($row["prepayment"], 0, ',', ' '); ?></td>
                <td><a href="#">отмена</a></td>
        </tr>

    <?php }
  }
}
if (isset($_POST['rowpredo'])) {
  $start = $_POST['rowpredo'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM debts WHERE main_prepayment != '0' AND sts='2' ORDER BY id desc LIMIT ".$start.",".$limit;
  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['del'] == 0) {
          $sts = "Принят";
          $sts_color = "green";
          $sts_display = "inline-block";
      }else {
          $sts = "Отменень";
          $sts_color = "red";
          $sts_display = "none";
      }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
          <td><?php echo $row['id']; ?></td>
          <td><?php $user = get_contractor($connect, $row["id_counterpartie"]); echo $user["name"];?></td>
          <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>

          <td><?php echo $row['payment_type']; ?></td>
          <td><?php echo number_format($row['main_prepayment'], 0, ',', ' '); ?></td>
          <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>

          </tr>
          <tr>
          <td colspan="12" style="border:0px; background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
              <a href="prepayment_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
              <a href="prepayment_edit.php?id=<?php echo $row["id"]; ?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom">Редактировать</button> </a>
              <a href="action.php?change_del_prepayment_id=<?=$row['id']?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom" onclick="return confirm('Отменить?')">Отменить</button> </a>
          </td>   
        </tr>
    <?php 
    }
  }
}

if (isset($_POST['rowopsup'])) {
  $start = $_POST['rowopsup'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM supplier WHERE debt!='0' ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['del'] == 0) {
        $sts = "Принят";
        $sts_color = "green";
        $sts_display = "inline-block";
    }else {
        $sts = "Отменень";
        $sts_color = "red";
        $sts_display = "none";
    }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
                <td><?php echo $row['id']; ?></td>
                <td><?php $user = get_supplier($connect, $row["id_supplier"]); echo $user["name"];?></td>
                <td><?php echo $date = date("d.m.Y", strtotime($row["order_date"])); ?></td>
                <td><?php echo $row['payment_type']; ?></td>
                <td><?php echo number_format($row['debt'], 0, ',', ' '); ?></td>
                <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
            </tr>
            <tr>
                <td colspan="12" style="border:0px; background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <a href="supplier_pay_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                    <a href="supplier_pay_edit.php?id=<?php echo $row["id"]; ?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom">Редактировать</button> </a>
                    <a href="action.php?change_del_supplier_id=<?=$row['id']?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom" onclick="return confirm('Отменить?')">Отменить</button> </a>
                </td>   
            </tr>

    <?php }
  }
}

if (isset($_POST['rowcashin'])) {
  $start = $_POST['rowcashin'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM cashbox WHERE sum_in != '0' ORDER BY id desc LIMIT ".$start.",".$limit;
  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['del'] == 0) {
          $sts = "Принят";
          $sts_color = "green";
          $sts_display = "inline-block";
      }else {
          $sts = "Отменень";
          $sts_color = "red";
          $sts_display = "none";
      }
      ?>
         <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $name  = get_status_in($connect, $row["types_id"]); echo $name["name"]; ?></td>
            <td><?php echo $row['type_payment']; ?></td>
            <td><?php echo number_format($row['sum_in'], 0, ',', ' '); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["date_cash"])); ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
            </tr>
            <tr>
            <td colspan="12" style="border:0px; background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="cash_in_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="cash_in_edit.php?id=<?php echo $row["id"]; ?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_del_cash_in_id=<?=$row['id']?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom" onclick="return confirm('Отменить?')">Отменить</button> </a>
            </td>   
        </tr>
    <?php }
  }
}

if (isset($_POST['rowcashout'])) {
  $start = $_POST['rowcashout'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM cashbox WHERE sum_out != '0' ORDER BY id desc LIMIT ".$start.",".$limit;
  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['del'] == 0) {
          $sts = "Принят";
          $sts_color = "green";
          $sts_display = "inline-block";
      }else {
          $sts = "Отменень";
          $sts_color = "red";
          $sts_display = "none";
      }
      ?>
         <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
              <td><?php echo $row["id"]; ?></td>
              <td><?php $name  = get_status_out($connect, $row["types_id"]); echo $name["name"]; ?></td>
              <td><?php echo $row['type_payment']; ?></td>
              <td><?php echo number_format($row['sum_out'], 0, ',', ' '); ?></td>
              <td><?php echo $date = date("d.m.Y", strtotime($row["date_cash"])); ?></td>
              <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
          </tr>
          <tr>
              <td colspan="12" style="border:0px; background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                  <a href="cash_out_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                  <a href="cash_out_edit.php?id=<?php echo $row["id"]; ?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom">Редактировать</button> </a>
                  <a href="action.php?change_del_cash_out_id=<?=$row['id']?>"><button style="display: <?php echo $sts_display; ?>" class="btn btn-custom" onclick="return confirm('Отменить?')">Отменить</button> </a>
              </td>   
          </tr>
    <?php }
  }
}

if (isset($_POST['roworder'])) {
  $start = $_POST['roworder'];
  $i = $_POST['i'];
  $i = $start;
  $limit = 15;
  // $query = "SELECT * FROM main_ord_tbl WHERE order_status='0' OR order_status='1' ORDER BY id desc LIMIT ".$start.",".$limit;
  $query = "SELECT * FROM main_ord_tbl WHERE order_status IN ('0', '1', '6') ORDER BY id DESC LIMIT ".$start.",".$limit;


  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row["order_status"] == 0) {
        $status = 'Новый';
        $btn = 'new';
        $display_btn1 = 'true';
        $display_btn2 = 'none';
        $display_btn3 = 'true';
        $display_btn4 = 'true';
        $display_btn5 = 'none';
        $display_btn6 = 'true';
      }elseif ($row["order_status"] == 6) {
          $status = 'Готов';
          $btn = 'ready';
          $display_btn1 = 'true';
          $display_btn2 = 'none';
          $display_btn3 = 'true';
          $display_btn4 = 'true';
          $display_btn5 = 'none';
          $display_btn6 = 'none';
      }
      elseif ($row["order_status"] == 1) {
          $status = 'Доставлено';
          $btn = 'delivered';
          $display_btn1 = 'none';
          $display_btn2 = 'true';
          $display_btn3 = 'none';
          $display_btn4 = 'none';
          $display_btn5 = 'true';
          $display_btn6 = 'none';
      }
      ?>
      <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
          
          <td><?php echo $row["id"]; ?></td>
          <td><?php $user = get_contractor($connect, $row["contractor"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
          <td><?php $user = get_user($connect, $row["sale_agent"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
          <td><?php echo $date = date("d.m.Y", strtotime($row["ord_date"])); ?></td>
          <td><?php echo $date = date("d.m.Y", strtotime($row["ord_deliver_date"])); ?></td>
          <td><?php echo $row["payment_type"]; ?></td>
          <td><?php echo number_format($row['transaction_amount'], 0, '.', ' '); ?></td>  
          <td><span class="status <?php echo $btn?>"><?php echo $status?></span></td>
      </tr>
      <tr>
          <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
              <!-- btn view -->
              <a href="view_inside_order-sklad.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["ord_date"]; ?>&&del_date=<?php echo $row["ord_deliver_date"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
              <!-- btn ready -->
              <a href="action-sklad.php?ready_id=<?=$row['id']?>&&contractor_id=<?=$row['contractor']?>&&debt=<?=$row['transaction_amount']?>&&ord_deliver_date=<?=$row['ord_deliver_date']?>&&payment_type=<?=$row['payment_type']?>"><button style="display:<?php echo $display_btn6; ?>" onclick="return confirm('Готов?')" class="btn btn-custom">Готов</button> </a>
              <!-- btn delivered -->
              <a href="action-sklad.php?archive_id=<?=$row['id']?>&&contractor_id=<?=$row['contractor']?>&&debt=<?=$row['transaction_amount']?>&&ord_deliver_date=<?=$row['ord_deliver_date']?>&&payment_type=<?=$row['payment_type']?>"><button style="display:<?php echo $display_btn1; ?>" onclick="return confirm('Доставлено?')" class="btn btn-custom">Доставлено</button> </a>
          </td>
      </tr>
    <?php }
  }
}
if (isset($_POST['rowstore'])) {
  $start = $_POST['rowstore'];
  $i = $_POST['i'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM order_tbl ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row["status_order"] == 1) {
        $status_order = 'Принят';
        $status_btn = 'Черновик';
        $color = '#5cb85c';
        $dsp_toggle = 'none';

    }elseif ($row["status_order"] == 0) {
        $status_order = 'Черновик';
        $status_btn = 'Принят';
        $color = 'silver';
        $dsp_toggle = 'true';
    }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $supplier = get_supplier($connect, $row["supplier_id"]);?>&nbsp;<?php echo $supplier["name"];?></td>
            <td><?php echo number_format(floatval($row['sum_order']), 0, '.', ' '); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["date_order"])); ?></td>
            <td><span style="border: 1px solid; background-color: <?php echo $color;?>; padding: 5px 10px; border-radius: 4px; color: white;"><?php echo $status_order; ?></span></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow">
                <div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                    <a href="view_prod-sklad.php?id=<?php echo $row["id"]; ?>&&date=<?php echo $row["date_order"]; ?>&&sum=<?php echo $row["sum_order"]; ?>&&note=<?php echo $row["order_note"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="edit_pro.php?id=<?php echo $row["id"]; ?>&&date=<?php echo $row["date_order"]; ?>&&sum=<?php echo $row["sum_order"]; ?>&&note=<?php echo $row["order_note"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="action.php?store_id=<?=$row['id']?>&&supplier_id=<?=$row['supplier_id']?>&&credit=<?=$row['sum_order']?>&&ord_date=<?=$row['date_order']?>&&payment_type=<?=$row['payment_type']?>"><button onclick="return confirm('Принят?')" class="btn btn-custom">Принят</button> </a>
                    <a style="display:<?php if($row["status_order"] == 1){echo 'true';}else {echo 'none';}?>;" href="action.php?draft_store_id=<?=$row['id']?>&&supplier_id=<?=$row['supplier_id']?>&&credit=<?=$row['sum_order']?>&&ord_date=<?=$row['date_order']?>&&payment_type=<?=$row['payment_type']?>"><button onclick="return confirm('Черновик?')" class="btn btn-custom">Черновик</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0){echo 'true';}else {echo 'none';}?>;" href="action.php?cencel_id_store=<?=$row['id']?>"><button onclick="return confirm('Отменить?')" class="btn btn-custom">Отменить</button> </a>
                    <a style="display:<?php if($row["status_order"] == 0 OR $row["status_order"] == 1){echo 'true';}else {echo 'none';}?>;" href="#" class="btn btn-custom">Счет-фактура</button> </a>
                </div> 
            </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowreturn'])) {
  $start = $_POST['rowreturn'];
  $i = $_POST['i'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM return_list ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row["return_status"] == 0) {
         $status_order = 'Принят';
        $status_type = 'archived';
        $dsp_toggle = 'none';

      }elseif ($row["return_status"] == 1) {
            $status_order = 'Удален';
            $status_type = 'cancelled';
            $dsp_toggle = 'none';
      }
      ?>
      <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $user = get_contractor($connect, $row["contractor"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php $user = get_user($connect, $row["sale_agent"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["return_date"])); ?></td>
            <td><?php echo $row["payment_type"]; ?></td>
            <td><?php echo number_format($row['transaction_amount'], 0, '.', ' '); ?></td>
            <td><span class='status <?php echo $status_type; ?>'><?php echo $status_order; ?></span></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="view_inside_return.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["return_date"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a style="display:<?php if($row["return_status"] == 0){echo 'true';}else {echo 'none';}?>;" href="edit_inside_return.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["return_date"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a style="display:<?php if($row["return_status"] == 0){echo 'true';}else {echo 'none';}?>;" href="action.php?return_delete_id=<?=$row['id']?>"><button onclick="return confirm('Отменить?')" class="btn btn-custom">Удалить</button> </a>
                <a style="display:<?php if($row["return_status"] == 0){echo 'true';}else {echo 'none';}?>;" href="#" class="btn btn-custom">Накладные</button> </a>
            </div> </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowarchive'])) {
  $start = $_POST['rowarchive'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM main_ord_tbl WHERE order_status='4' ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      ?>
          <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $user = get_contractor($connect, $row["contractor"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php $user = get_user($connect, $row["sale_agent"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_date"])); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_deliver_date"])); ?></td>
            <td><?php echo $row["payment_type"]; ?></td>
            <td><?php echo number_format($row['transaction_amount'], 0, '.', ' '); ?></td>
            <td><span class="status archived">Архив</span></td>
        </tr>
        <tr >
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="inside_archive_order.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["ord_date"]; ?>&&del_date=<?php echo $row["ord_deliver_date"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="action.php?restore_id=<?=$row['id']?>"><button onclick="return confirm('Восстановить?')" class="btn btn-custom">Восстановить</button> </a>
            </div> </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowdel'])) {
  $start = $_POST['rowdel'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM main_ord_tbl WHERE order_status='2' ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      ?>
          <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            <td><?php echo $row["id"]; ?></td>
            <td><?php $user = get_contractor($connect, $row["contractor"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php $user = get_user($connect, $row["sale_agent"]);?>&nbsp;<?php echo $user["surname"]; ?>&nbsp;<?php echo $user["name"]; ?>&nbsp;<?php echo $user["fathername"]; ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_date"])); ?></td>
            <td><?php echo $date = date("d.m.Y", strtotime($row["ord_deliver_date"])); ?></td>
            <td><?php echo $row["payment_type"]; ?></td>
            <td><?php echo number_format($row['transaction_amount'], 0, '.', ' '); ?></td>
            <td><span class="status cancelled">Отменен</span></td>
        </tr>
        <tr >
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="inside_deleted_order.php?id=<?php echo $row["id"]; ?>&&payment_type=<?php echo $row["payment_type"]; ?>&&sale_agent=<?php echo $row["sale_agent"]; ?>&&contractor=<?php echo $row["contractor"]; ?>&&date=<?php echo $row["ord_date"]; ?>&&del_date=<?php echo $row["ord_deliver_date"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
            </div> </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowcounterpartie'])) {
  $start = $_POST['rowcounterpartie'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM counterparties_tbl ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["alternative_name"]; ?></td>
            <td><?php echo $row["inn"]; ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="counterpartie_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="counterpartie_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_sts_counterpartie_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
            </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowsupplier'])) {
  $start = $_POST['rowsupplier'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM supplier_tbl ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["alternative_name"]; ?></td>
            <td><?php echo $row["inn"]; ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="supplier_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="supplier_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_sts_supplier_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
            </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowproduct'])) {
  $start = $_POST['rowproduct'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM products_tbl ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">
            
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td><?php echo $row["unit"]; ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="product_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="product_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_sts_product_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
            </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowuser'])) {
  $start = $_POST['rowuser'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM users_tbl ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle"> 
        <td><?php echo $row["id"]; ?></td>
        <td><?php echo $row["surname"]; ?>&nbsp;<?php echo $row["name"];?>&nbsp;<?php echo $row["fathername"]; ?></td>
        <td><?php echo $row["login"]; ?></td>
        <td><?php 
            if ($row["role"]=='administrator') {
               echo "Администратор";
            }elseif ($row["role"]=='operator') {
               echo "Оператор";
            }elseif ($row["role"]=='sale') {
                echo "Торговый представитель";
             }elseif ($row["role"]=='storekeeper') {
                echo "Складовик";
             }
            ?></td>
        <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
    </tr>
    <tr>
        <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
            <a href="user_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
            <a href="user_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
            <a href="action.php?change_sts_user_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
        </td>
    </tr>
    <?php }
  }
}

if (isset($_POST['rowtci'])) {
  $start = $_POST['rowtci'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM state_in ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
        <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">    
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="tci_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="tci_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_sts_tci_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
            </td>
        </tr>
    <?php }
  }
}

if (isset($_POST['rowtco'])) {
  $start = $_POST['rowtco'];
  $i = $start;
  $limit = 15;
  $query = "SELECT * FROM state_out ORDER BY id desc LIMIT ".$start.",".$limit;

  $result = mysqli_query($connect,$query);

  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $i++;
      if ($row['sts'] == 1) {
        $sts = "Активный";
        $sts_color = "green";
     }else {
         $sts = "Не активный";
        $sts_color = "black";
     }
      ?>
         <tr data-toggle="collapse" data-target="#row<?php echo $i;?>" aria-expanded="true" class="accordion-toggle">    
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["name"]; ?></td>
            <td style="color: <?php echo $sts_color; ?>"><?php echo $sts; ?></td>
        </tr>
        <tr>
            <td colspan="12" style="border:0px;  background-color: #fafafb;" class="hiddenRow"><div class="accordian-body collapse" id="row<?php echo $i;?>"> 
                <a href="tco_view.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Просмотр</button> </a>
                <a href="tco_edit.php?id=<?php echo $row["id"]; ?>"><button class="btn btn-custom">Редактировать</button> </a>
                <a href="action.php?change_sts_tco_id=<?=$row['id']?>"><button class="btn btn-custom" onclick="return confirm('Изменить?')">Изменить стутус</button> </a>
            </td>
        </tr>
    <?php }
  }
}






?>
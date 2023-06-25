<?php
   session_start();
   unset($_SESSION['usersname']);
   header('Refresh: 0; URL = index.php');
?>
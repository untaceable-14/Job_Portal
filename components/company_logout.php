<?php
  include 'connect.php';
  session_start();
  session_unset();
  session_destroy();

  header('location:../companies/admin_login.php');
?>
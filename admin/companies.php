<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:admin_login.php');
};
if(isset($_POST['update_payment'])){
  $order_id = $_POST['order_id'];
  $payment_status = $_POST['payment_status'];
  $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ? ");
  $update_payment->execute([$payment_status, $order_id]);
  $message[] = 'payment status updated!';
}

if(isset($_POST['update_order'])){
  $order_id = $_POST['order_id'];
  $order_status = $_POST['order_status'];
  $order_status = filter_var($order_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET order_status = ? WHERE id = ? ");
  $update_payment->execute([$order_status, $order_id]);
  $message[] = 'payment status updated!';
}
if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
  $delete_order->execute([$delete_id]);
  header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placed Orders</title>
  <link rel="icon" href="../images/ecommerce logo.png">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <link rel="stylesheet" href="../css/admin_style.css">
    
  </head>
  <body>
<?php include '../components/admin_header.php' ?>
 


<h1 class="heading">Search by company</h1>
<section class="orders" >
   <?php
$select_products = $conn->prepare("SELECT * FROM `companies`"); 
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
   <center> 
 <div class="box-container">
    <div class="box">
    <a href="category_company.php?category=<?= $fetch_product['company_name']; ?>"> <p><span><?= $fetch_product['company_name']; ?></p></span> </a>
   </center>
   </div><br>
</div>
<?php 
      }
   }else{
      echo '<p class="empty">no companies found !</p>';
   }
   ?>
   
   </form>
</section>



  <script src="../js/admin_script.js"></script>   
  <Footer>
   <center><p class="empty " style="margin-top: 10rem; ">All rights reserved to Niteesh &copy; <?= date('M Y')?></p> </center>
</Footer>
</body>
</html>


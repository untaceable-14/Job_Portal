<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];


$select_products = $conn->prepare("SELECT * FROM `companies` where id = ?"); 
$select_products->execute([$admin_id]);
$fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);

$com = $fetch_product['company_name'];
if(!isset($admin_id)){
  header('location:admin_login.php');
};
if(isset($_POST['update_status'])){
  $order_id = $_POST['order_id'];
  $payment_status = $_POST['verify_status'];
  $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
  $update_payment = $conn->prepare("UPDATE `orders` SET application_status = ? WHERE id = ? ");
  $update_payment->execute([$payment_status, $order_id]);
  $message[] = 'application status updated!';
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
<?php include '../components/company_header.php' ?>
 
<section class="orders">

<h1 class="heading">Applications</h1>

<div class="box-container">
<?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` where company_name = ?");
      $select_orders->execute([$com]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   
   <div class="box">
      <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <!-- <p> Address : <span><?= $fetch_orders['address']; ?></span> </p> -->
      <p> Job name : <span><?= $fetch_orders['job_name']; ?></span> </p>
      <p> Company name : <span><?= $fetch_orders['company_name']; ?></span> </p>
      <p> Description : <span><?= $fetch_orders['details']; ?></span> </p>
      <p> Salary : <span>₹<?= $fetch_orders['salary']; ?>/-</span> </p>
      <p> Location : <span><?= $fetch_orders['location']; ?>/-</span> </p>
      <p>Application status :</p>
      <form action="" method="post">
      <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="verify_status" class="select" default>
            <option selected disabled><?= $fetch_orders['application_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="selected">selected</option>
            <option value="approved-for-exam">approved</option>
            <option value="rejected">not selected</option>
          </select>
         <div class="flex-btn">
          <input type="submit" value="update" class="option-btn" name="update_status" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?>
        </div>
        
          <!-- <div class="flex-btn">
          <input type="submit" value="order" class="option-btn" name="update_order" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?> -->
        </div>
     
   <?php
         }
      }else{
         echo '<p class="empty">no applications applied yet!</p>';
      }
   ?>
      </form>
   </div>

</div>


</section>



  <script src="../js/admin_script.js"></script>   
  <Footer>
   <center><p class="empty " style="margin-top: 10rem; ">All rights reserved to Niteesh &copy; 2023</p> </center>
</Footer>
</body>
</html>


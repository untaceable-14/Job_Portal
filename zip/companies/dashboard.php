<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
  <link rel="icon" href="../images/ecommerce logo.png">
   
</head>
<body>
   <?php include '../components/company_header.php' ?>
   <section class="dashboard">
      
      <h1 class="heading">Dashboard</h1>
    <div class="box-container">
     <div class="box">
      <h3>Welcome!</h3>
      <p style="border: .2rem solid #000;"><?= $fetch_profile['adminname'];?></p>
      <a href="update_profile.php" class="btn">Update Profile</a>
    
   </div>  
      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders` where company_name = ?");
            $select_orders->execute([$fetch_profile['company_name']]);
            $number_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p style="border: .2rem solid #000;">Total application</p>
         <a href="placed_orders.php" class="btn">see applications</a>
      </div>
      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products` where company_name = ?");
            $select_products->execute([$fetch_profile['company_name']]);
            $number_of_products = $select_products->rowCount();
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p style="border: .2rem solid #000;">Total Jobs</p>
         <a href="products.php" class="btn">see jobs</a>
      </div>
      
      
      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages` where company_name = ?");
            $select_messages->execute([$fetch_profile['company_name']]);
            $number_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p style="border: .2rem solid #000;">Messages</p>
         <a href="messages.php" class="btn">see messages</a>
      </div>
   </div>
  </section>

  <script src="../js/admin_script.js"></script>   
  <Footer>
     <center><p class="empty " style="margin-top: 15rem;  ">All rights reserved to Niteesh &copy; <?= date('M Y'); ?></p> </center>
</Footer>
</body>
</html>


<!-- <pre><div style="color: red;" >Niteesh'<small>s</small></div>  E-commerce  Dashboard</pre> -->
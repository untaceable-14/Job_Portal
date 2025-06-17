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
   <center>
   <section class="orders">
   <?php
   $a=$_GET['category'];
$select_products = $conn->prepare("SELECT * FROM `products` where company_name = ?"); 
   $select_products->execute([$a]);
   if($select_products->rowCount() > 0){
      $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
   }
         ?>
       <div class="box-container">
           <div class="box"><a href="category_job.php?category=<?= $fetch_product['company_name']; ?>"><p><span>Jobs</span></p></a></div>
       </div><br>
       <div class="box-container">  
           <div class="box"><a href="category_application.php?category=<?= $fetch_product['company_name']; ?>"><p><span>Applications</span></p></a></a></div> 
       
    </div>
    </center>
   </section>
   
  <script src="../js/admin_script.js"></script>   
  <Footer>
     <center><p class="empty " style="margin-top: 15rem;  ">All rights reserved to Niteesh &copy; <?= date('M Y'); ?></p> </center>
</Footer>
</body>
</html>
<?php

include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/apply.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Category</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="images/ecommercelogocopy.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>

<section class="products">

   
   <div class="box-container">
      <?php
   $category = $_GET['category'];
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE job_name LIKE '%{$category}%'"); 
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
         <h1 class="heading">Category</h1>
         <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['company_name']; ?>">
      <div class="name"><center><?= $fetch_product['job_name']; ?></center></div>
      <div class="flex">
         <div class="price"><span class="name">Salary :- </span><span>₹</span><?= $fetch_product['salary']; ?><span>/-</span></div>
      </div>
      <div class="flex">
         <div class="name">Location :- <span class="price"><?= $fetch_product['location']; ?></span></div></div>
         <div class="flex">
            <div class="name">Company :- <span class="price"><?= $fetch_product['company_name']; ?></span></div></div>
            <div class="flex-btn">
            <a href="quick_view3.php?pid=<?= $fetch_product['id']; ?>" class="btn">view job</a>
            <a href="apply.php?pid=<?= $fetch_product['id']; ?>" class="option-btn" name="apply_now_job">apply</a></div>
            <a href="index.php" class="delete-btn"> Go Back</a>
            </form>
   <?php 
      }
   }else{
      echo '<p class="empty">no jobs found !</p>';
   }
   ?>
 </div>

</section>







<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
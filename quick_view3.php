<?php

include 'components/connect.php';
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick View</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>



<div class="flex-btn">

<section class="quick-view">

   <h1 class="heading">quick view</h1>
<div class="box-container">
  
   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="job_name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['company_name']; ?>">
      <div class="row">
         
         <div class="content">
            <div class="name"><?= $fetch_product['job_name']; ?></div>
            <div class="flex">
               <div class="price"><span>₹</span><?= $fetch_product['salary']; ?><span>/-</span></div>
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="details"><?= $fetch_product['company_name']; ?></div>
            <div class="details"><?= $fetch_product['location']; ?></div>
            <div class="name">Required Skills :-</div>
            <div class="details"><?= $fetch_product['required_skills']; ?></div>
            <div class="flex-btn">
               <a href="category.php?category=<?= $fetch_product['job_name']; ?>" class="delete-btn">go back</a>
      <a href="apply.php?pid=<?= $fetch_product['id']; ?>" class="option-btn" name="apply_now_job">apply now</a>
              
            </div>
         </div>
         <a href="contact4.php?pid=<?= $fetch_product['job_name']; ?>&&pi=<?= $fetch_product['company_name']; ?>" class="btn">contact us</a>
      </div>
   </form>
   </div>
</div>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

</section>


</div>

<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script_copy.js"></script>


</body>
</html>
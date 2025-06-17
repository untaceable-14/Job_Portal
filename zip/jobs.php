<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>
<?php
    if(isset($_SESSION['pop-up'])){

      $message[] = 'Logged in sucessfully';

    unset($_SESSION['pop-up']);
}?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/ecommerce logo.png">
   <link rel="stylesheet" href="css/user_style.css">

</head>
  
<body>
   
<?php include 'components/user_header.php'; ?>


<?php include 'search_page.php'; ?>









<h1 class="heading">Latest jobs</h1>
<section class="products">
   
   
   <div class="box-container">
      <?php
   // $category = $_GET['category'];
   $select_products = $conn->prepare("SELECT * FROM `products`"); 
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
         <div class="flex">
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
            <a href="quick_view2.php?pid=<?= $fetch_product['id']; ?>" class="btn">view job</a>
            <a href="apply.php?pid=<?= $fetch_product['id']; ?>" class="option-btn" name="apply_now_job">apply </a></div>
            </form>
   </div>
   <?php 
      }
   }else{
      echo '<p class="empty">no jobs found !</p>';
   }
   ?>
   </div>

</section>


<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
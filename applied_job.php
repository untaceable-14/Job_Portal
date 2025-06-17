
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


<h1 class="heading">My Application</h1>
<section class="products">

   
   <div class="box-container">
      <?php
   $category = $_GET['oid'];
   $select_products = $conn->prepare("SELECT * FROM `orders` WHERE id LIKE '%{$category}%'"); 
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
         <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['company_name']; ?>">
      <div class="name"><center> <?= $fetch_product['job_name']; ?></center></div>
      <div class="flex">
         <div class="price"><span class="name">Salary :- </span><span>₹</span><?= $fetch_product['salary']; ?><span>/-</span></div>
      </div>
      <div class="flex">
         <div class="name">Location :- <span class="price"><?= $fetch_product['location']; ?></span></div></div>
         <div class="flex">
            <div class="name">Job Description:- <span class="price"><?= $fetch_product['details']; ?></span></div></div>
            <div class="flex">
            <div class="name">Company :- <span class="price"><?= $fetch_product['company_name']; ?></span></div></div>
           <div class="flex">
            <div class="name">Applied On :- <span class="price"><?= $fetch_product['placed_on']; ?></span></div></div>
            <div class="flex">
            <div class="name">Application status :- <span class="price"><?= $fetch_product['application_status']; ?></span></div></div>
            <?php
            if($fetch_product['application_status'] == 'approved-for-exam'){
               ?><br>
      

               <div class="flex">
                  <a href="test_instructions.php" class="btn">Take test</a>
<!-- exam.php?a=<?= $fetch_product['company_name']; ?> -->
               </div>
               <?php
            } 
            ?>
            <!--  <div class="flex">
            <div class="name">Company :- <span class="price"><?= $fetch_product['company_name']; ?></span></div></div> -->
            <div class="flex">
             <a href="applications.php" class="option-btn">Go Back</a>
            </div>
        </form>
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
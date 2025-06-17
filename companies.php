
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

<h1 class="heading">Search by company</h1>
<section class="products1" style="height: fit-content;">
   <?php
$select_products = $conn->prepare("SELECT * FROM `companies` where company_name != '' GROUP BY company_name"); 
   $select_products->execute();
   if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         ?>
   <center> <a href="category_company.php?category=<?= $fetch_product['company_name']; ?>">
 <div class="box-container1">
    <div class="box1">
      <div class="companies" style="color: red;"><?= $fetch_product['company_name']; ?> <br></div>
   </a></center>
   </div>
</div>
<?php 
      }
   }else{
      echo '<p class="empty">no jobs found !</p>';
   }
   ?>
   
   </form>
</section>
<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
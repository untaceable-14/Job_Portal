
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

<?php include 'components/apply.php'; ?>
<?php include 'search_page.php'; ?>
<div class="flex-btn">

<section class="orders">

   <h1 class="heading">my applications</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<section class="orders"><a href="user_login.php"><p class="empty">please login to see your applications</p></a></section>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <!-- <p>Name : <span><?= $fetch_orders['id']; ?></span></p> -->
      <!-- <p>Name : <span><?= $fetch_orders['name']; ?></span></p> -->
      <!-- <p>Email : <span><?= $fetch_orders['email']; ?></span></p> -->
      <!-- <p>Number : <span><?= $fetch_orders['number']; ?></span></p> -->
      <!-- <p>Address : <span><?= $fetch_orders['address']; ?></span></p> -->
      <p>Company Name : <span><?= $fetch_orders['company_name']; ?></span></p>
      <p>Role : <span><?= $fetch_orders['job_name']; ?></span></p>
      <!-- <p>Salary : <span>₹<?= $fetch_orders['salary']; ?>/-</span></p> -->
      <!-- <p>Location : <span>₹<?= $fetch_orders['location']; ?>/-</span></p> -->
      <!-- <p>Application Status : <span><?= $fetch_orders['application_status']; ?></span></p> -->
      <!-- <p>Applied on : <span><?= $fetch_orders['placed_on']; ?></span></p> -->
      <a href="applied_job.php?oid=<?= $fetch_orders['id']; ?>" 
   class="btn <?= ($fetch_orders['application_status'] == 'completed') ? 'disabled' : ''; ?>">
   View Application
</a>
   </div>
   <?php
      }
      }else{
         echo '<section class="orders"><p class="empty">No applications yet! <a href="jobs.php" class="btn"> start applying for jobs</a></p></section>';
         
      }
      }
   ?>

   </div>

   </section>
</div>
<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
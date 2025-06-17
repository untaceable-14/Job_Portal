<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $skills = $_POST['skills'];
   $skills = filter_var($skills, FILTER_SANITIZE_STRING);
   $com_name = $_POST['com_name'];
   $com_name = filter_var($com_name, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `products` SET job_name = ?,salary = ?, details = ?,  required_skills = ?, company_name = ?, location = ?  WHERE id = ?");
   $update_product->execute([$name, $price, $details, $skills, $com_name, $location, $pid]);

   $message[] = 'product updated successfully!';
 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="../images/ecommerce logo.png">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/company_header.php'; ?>

<section class="update-product">

   <h1 class="heading">update product</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <span>update job name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter job name" value="<?= $fetch_products['job_name']; ?>">
      <span>update salary</span>
      <input type="text" name="price" required class="box"  placeholder="enter salary" value="<?= $fetch_products['salary']; ?>">
      <span>update description</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <span>update skills</span>
      <input type="text" name="skills" required class="box"  placeholder="enter skills" value="<?= $fetch_products['required_skills']; ?>">  
      <span>update company name</span>
      <input type="text" name="com_name" required class="box"  placeholder="enter company name" value="<?= $fetch_products['company_name']; ?>">
      <span>update location</span>
      <input type="text" name="location" required class="box"  placeholder="enter location" value="<?= $fetch_products['location']; ?>">
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="jobs.php" class="option-btn">go back</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">no product found!</p>';
      }
   ?>

</section>












<script src="../js/admin_script.js"></script>
<Footer>
   <center><p class="empty " style="margin-top: 10rem;  ">All rights reserved to Niteesh &copy; 2023</p> </center>
</Footer>
</body>
</html>
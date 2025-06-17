
<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])&& isset($_FILES['image'])) {
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        
        $image_name1 = $_FILES['image']['name'];
        $tmp_name1 = $_FILES['image']['tmp_name'];
        $folder1 = "uploaded_img/" . $image_name1;
        
        $select_profile = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?");
        $select_profile->execute([$user_id]);
        if (move_uploaded_file($tmp_name1, $folder1)) {
            // Insert into database
            $sql = $conn->prepare("UPDATE `users_details` SET profile_img=? WHERE user_id=?");
            $sql->execute([$image_name1, $user_id]);


            $message[]= "Profile updated successfully! 😊";
            header('Location: update_profile.php');
        } else {
            $message[]= "Error updating file.";
        }
    } else {
        $messge[]= "File upload error: " . $_FILES['image']['error'];
    }
}


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


<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Profile</h3>
      <?php
$select_products = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?"); 
   $select_products->execute([$user_id]);
   if($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
        
         if($fetch_product["profile_img"] == ''){
            echo '<p class="empty">add a profile picture !</p>';
         }else{ 
            ?>
<img src="uploaded_img/<?=($fetch_product["profile_img"]); ?>" class="circle" alt="">
<?php } ?>
<?php 
      }
  
   ?>
   
   <input type="file" name="image" accept="jpg/jpeg/png" class="box" required></div>
 
        <input type="submit" value="save" class="btn" name="upload">
      <div class="flex-btn">
      <a href="update_profile.php" class="option-btn">Go back</a>
   </div>
   </form>
</section>


<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>

<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $education = $_POST['education'];
    $passoutyr = $_POST['passoutyr'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];

    // Update user details in `users` table
    $update_profile = $conn->prepare("UPDATE `users` SET user_name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $user_id]);

    // Check if user details exist in `users_details`
    $select_profile = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?");
    $select_profile->execute([$user_id]);

    if ($select_profile->rowCount() > 0) {
        // Update existing user details
        $sql = $conn->prepare("UPDATE `users_details` 
                               SET name=?, email=?, dob=?, education=?, passout=?, location=?, number=? 
                               WHERE user_id=?");
        $sql->execute([$name, $email, $dob, $education, $passoutyr, $location, $phone, $user_id]);
        $update_profile = $conn->prepare("UPDATE `abc` SET name = ? WHERE id = ?");
        $update_profile->execute([$name, $user_id]);
        $message[]= "Profile updated successfully! 😊";
        header('Location: user_profile.php');
       
    } else {
        $message[]= "User details not found!";
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
   else{
      echo '<p class="empty">add picture !</p>';
   }
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->rowCount() > 0 ? $select_profile->fetch(PDO::FETCH_ASSOC) : null;
   ?>
   
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?=($fetch_profile["user_name"]); ?>">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" value="<?=($fetch_profile["email"]); ?>">
      <input type="date" name="dob" class="box" value="<?=($fetch_product["dob"]); ?>" required >
      <input type="text" name="education" class="box" value="<?=($fetch_product["education"]); ?>" required placeholder="Enter your education">
      <input type="number" name="passoutyr" class="box" value="<?=($fetch_product["passout"]); ?>" maxlength="4" required placeholder="Pass Out Year">
        <input type="text" name="location" class="box" value="<?=($fetch_product["location"]); ?>" required placeholder="Enter your location">
        <input type="number" name="phone" class="box" value="<?=($fetch_product["number"]); ?>" required placeholder="Enter your phone number">
        <input type="submit" value="Update" class="btn" name="upload">
      <div class="flex-btn">
       <a href="update_img.php" class="btn">Update profile picture</a> 
      </div>
      <div class="flex-btn">
        <a href="update_res.php" class="btn">Update Resume</a> 
    </div>
      <div class="flex-btn">
      <a href="user_profile.php" class="option-btn">Go back</a>
   </div>
   </form>
</section>

<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>

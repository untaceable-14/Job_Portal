
<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
$select_profile = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->rowCount() > 0 ? $select_profile->fetch(PDO::FETCH_ASSOC) : null;

$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->rowCount() > 0 ? $select_profile->fetch(PDO::FETCH_ASSOC) : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload']) && isset($_FILES['resume']) && isset($_FILES['image'])) {
    if ($_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['resume']['name'];
        $tmp_name = $_FILES['resume']['tmp_name'];
        $folder = "uploaded_resumes/" . $image_name;
        $image_name1 = $_FILES['image']['name'];
        $tmp_name1 = $_FILES['image']['tmp_name'];
        $folder1 = "uploaded_img/" . $image_name1;
        $name=$_POST['name'];
        $email=$_POST['email'];
        $dob=$_POST['dob'];
        $education=$_POST['education'];
        $passoutyr=$_POST['passoutyr'];
        $location=$_POST['location'];
        $phone=$_POST['phone'];

        $select_profile = $conn->prepare("SELECT * FROM `users_details` WHERE user_id = ?");
        $select_profile->execute([$user_id]);
        if($select_profile->rowCount() > 0){
                $message[]= "Profile already saved !";
            }elseif ($_FILES['resume']['type'] !== 'application/pdf') {
            $message[]= "Please upload a valid PDF file.";
        } elseif (move_uploaded_file($tmp_name, $folder) && move_uploaded_file($tmp_name1, $folder1)) {
            // Insert into database
            $sql = $conn->prepare("INSERT INTO `users_details` (`user_id`,`name`,`email`, `dob`,`education`, `passout`, `location`, `number`, `profile_img`, `resume`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $sql->execute([$user_id,$name,$email, $dob, $education, $passoutyr, $location, $phone, $image_name1, $image_name]);
            

            $message[]= "Profile details added successfully! 😊";
        header('Location: user_profile.php');


        } else {
            $message[]= "Error moving uploaded file.";
        }
    } else {
        $messge[]= "File upload error: " . $_FILES['resume']['error'];
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
      <h3>Add details</h3>
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
    echo '<img src="images/user.png" class="box1" alt="">';
}
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->rowCount() > 0 ? $select_profile->fetch(PDO::FETCH_ASSOC) : null;
   ?>
   
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?=($fetch_profile["user_name"]); ?>">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" value="<?=($fetch_profile["email"]); ?>">
      <input type="date" name="dob" class="box"  required >
      <input type="text" name="education" class="box"  required placeholder="Enter your education">
      <input type="number" name="passoutyr" class="box"  maxlength="4" required placeholder="Pass Out Year">
        <input type="text" name="location" class="box"  required placeholder="Enter your location">
        <input type="number" name="phone" class="box"  required placeholder="Enter your phone number">
       <div class="flex-btn">
            <div class="box1">Profile image :-</div>
            <input type="file" name="image" accept="jpg/jpeg/png" class="box" required>
        </div>
        <div class="flex-btn">
            <div class="box1">Resume :-</div>
            <input type="file" name="resume" accept="application/pdf" class="box" required> 
        </div>
        <input type="submit" value="Add" class="btn" name="upload">
    
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

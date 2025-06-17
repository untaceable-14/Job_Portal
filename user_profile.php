<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_SESSION['pop-up'])) {
   $message[] = 'Logged in successfully';
   unset($_SESSION['pop-up']);
}

$conn = new PDO("mysql:host=localhost;dbname=final_project", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Profile fetching
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

            $message[]= "Profile updated successfully! 😊";
        } else {
            $message[]= "Error moving uploaded file.";
        }
    } else {
        $messge[]= "File upload error: " . $_FILES['resume']['error'];
    }
}
?>

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
         }else{ ?>
<img src="uploaded_img/<?=($fetch_product["profile_img"]); ?>" class="circle" alt="">
<?php } ?>
<?php 
      }
   else{
      echo '<img src="images/user.png" class="box1" alt="">';
   }
   ?>
   
      <!-- <input type="text" readonly name="a" required placeholder="enter your username" maxlength="20" class="box" value="<?=($fetch_profile["id"]); ?>"> -->
      <input type="text" readonly name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?=($fetch_profile["user_name"]); ?>">
      <input type="email" readonly name="email" required placeholder="enter your email" maxlength="50" class="box" value="<?=($fetch_profile["email"]); ?>">
      <?php if (!empty($fetch_product["dob"])): ?>
    <input type="date" name="dob" class="box" readonly 
           value="<?= htmlspecialchars($fetch_product["dob"]); ?>" required>
<?php endif; ?>

<?php if (!empty($fetch_product["education"])): ?>
    <input type="text" name="education" class="box" readonly 
           value="<?= htmlspecialchars($fetch_product["education"]); ?>" required>
<?php endif; ?>

<?php if (!empty($fetch_product["passout"])): ?>
    <input type="number" name="passoutyr" class="box" readonly maxlength="4"
           value="<?= htmlspecialchars($fetch_product["passout"]); ?>" required>
<?php endif; ?>

<?php if (!empty($fetch_product["location"])): ?>
    <input type="text" name="location" class="box" readonly 
           value="<?= htmlspecialchars($fetch_product["location"]); ?>" required>
<?php endif; ?>

<?php if (!empty($fetch_product["number"])): ?>
    <input type="number" name="phone" class="box" readonly 
           value="<?= htmlspecialchars($fetch_product["number"]); ?>" required>
<?php endif; ?>

<?php if (!empty($fetch_product["resume"])): ?>
    <!-- <div class="flex-btn"> -->
        <h3 class="box">Resume:</h3>
        <p>Current File: <a href="uploaded_resumes/<?= htmlspecialchars($fetch_product['resume']); ?>" target="_blank">View Resume</a></p>
        <input type="hidden" name="existing_resume" value="<?= htmlspecialchars($fetch_product['resume']); ?>">
      <!-- </div> -->
      <?php endif; ?>
<?php if (empty($fetch_product["resume"])): ?>

      <p class="empty">Enhance your profile</p>
      <?php endif; ?>

<!-- <input type="submit" value="save" class="btn" name="upload"> -->
      <div class="flex-btn">
      <a href="update_user.php" class="btn">Change password</a>
      
<?php 
if (!empty($fetch_product["resume"])){
    echo'<a href="update_profile.php" class="btn">Update profile</a>';
}else{
?>

      <a href="userdetails_add.php" class="btn">Add details</a>
      <?php } ?>
   </div>
   </form>
</section>


<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
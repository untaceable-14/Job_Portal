<?php

include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
  $user_id = $_SESSION['user_id'];
//   echo"hello";
}else{
  $user_id = '';
   header('location:user_login.php');
 
  
};


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['send'] )) {
    // Sanitize inputs securely
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = htmlspecialchars($_POST['number'], ENT_QUOTES, 'UTF-8');
    $msg = htmlspecialchars($_POST['msg'], ENT_QUOTES, 'UTF-8');
    $comp_name = $_GET['pid'];
    $job_name = '-';

    // Check if the message already exists
    $select_message = $conn->prepare("SELECT 1 FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $select_message->execute([$name, $email, $number, $msg]);

    if ($select_message->rowCount() > 0) {
        $message[] = "Message already sent!";
    } else {
        // Insert the message
        $insert_message = $conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message, company_name, job_name) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_message->execute([$user_id, $name, $email, $number, $msg, $comp_name, $job_name]);

        $message[] = "Message sent successfully!";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>



<section class="contact">

   <form action="" method="post">
      <h3>Get in touch with us</h3>
      <input type="text" name="name" placeholder="enter your name" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box">
      <input type="number" name="number" min="0" max="9999999999" placeholder="enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box">
      <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>
</section>
   



<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
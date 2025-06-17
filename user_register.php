<?php

include 'components/connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $raw_pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   // Password validation regex
   $password_pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,16}$/";

   if(!preg_match($password_pattern, $raw_pass)){
      $message[] = "Password must be 8-16 characters long, with at least one uppercase letter, one number, and one special character!";
   } else {
      $pass = sha1($raw_pass);
      $pass = filter_var($pass, FILTER_SANITIZE_STRING);
      $cpass = sha1($cpass);
      $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

      $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_user->execute([$email]);
      $row = $select_user->fetch(PDO::FETCH_ASSOC);

      if($select_user->rowCount() > 0){
         $message[] = 'Entered email is already exists!';
      } else {
         if($pass != $cpass){
            $message[] = "Passwords don't match!";
         } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(user_name, email, password) VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            $insert_user1 = $conn->prepare("INSERT INTO `abc`(name, payment_status) VALUES(?,?)");
            $insert_user1->execute([$name, 'pending']);
            $message[] = 'Registered successfully, login now please!';
            header('location:user_login.php');
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration Page</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="ecommerce logo.png">

   <style>
      .error {
         color: red;
         font-size: 0.9em;
         margin-top: 5px;
         display: none;
      }
      .form-container .box.error-border {
         border: 2px solid red;
      }
      .password-container {
         position: relative;
      }
      .toggle-password {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         font-size: 3rem;
         color: #333;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>

<section class="form-container">

<form action="" method="post" onsubmit="return validatePassword()">
<h3>Registration here</h3>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <div class="password-container">
         <input type="password" id="pass" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <span class="toggle-password" onclick="togglePassword('pass')">&#128065;</span>
      </div>
      <p id="pass-error" class="error">Password must be 8-16 characters, with at least 1 uppercase letter, 1 number, and 1 special character.</p>

      <div class="password-container">
         <input type="password" id="cpass" name="cpass" required placeholder="confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <span class="toggle-password" onclick="togglePassword('cpass')">&#128065;</span>
      </div>
      <p id="cpass-error" class="error">Passwords do not match.</p>

      <input type="submit" value="register now" class="btn" name="submit">
      <p>Already have an account?</p>
      <a href="user_login.php" class="option-btn">Login here</a>

</form>
</section>

<!-- 
<?php 
include 'components/user_footer.php';
?> -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>

<script>
function validatePassword() {
   const pass = document.getElementById('pass');
   const cpass = document.getElementById('cpass');
   const passError = document.getElementById('pass-error');
   const cpassError = document.getElementById('cpass-error');

   const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,16}$/;

   let isValid = true;

   // Password validation
   if (!passwordPattern.test(pass.value)) {
      passError.style.display = 'block';
      pass.classList.add('error-border');
      isValid = false;
   } else {
      passError.style.display = 'none';
      pass.classList.remove('error-border');
   }

   // Confirm password validation
   if (pass.value !== cpass.value) {
      cpassError.style.display = 'block';
      cpass.classList.add('error-border');
      isValid = false;
   } else {
      cpassError.style.display = 'none';
      cpass.classList.remove('error-border');
   }

   return isValid;
}

function togglePassword(id) {
   const input = document.getElementById(id);
   const icon = input.nextElementSibling;

   if (input.type === 'password') {
      input.type = 'text';
      icon.innerHTML = '❌'; // eye icon
   } else {
      input.type = 'password';
      icon.innerHTML = '&#128065;'; // closed eye icon
   }
}
</script>

</body>
</html>

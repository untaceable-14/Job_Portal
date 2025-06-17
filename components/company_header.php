<?php
// error_reporting(0); 
$admin_id = $_SESSION['admin_id'];

if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
   <head>
   <link rel="icon" href="../images/ecommerce logo.png">
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
   

<header class="header">

   <section class="flex">
<?php
            $select_profile = $conn->prepare("SELECT * FROM `companies` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
      <a href="../admin/dashboard.php" class="logo">Company: <span><?=$fetch_profile['company_name'];?></span><br>Admin: <span><?= $fetch_profile['adminname']; ?></span></a>

      <nav class="navbar">
        
         <a  href="../companies/dashboard.php">Home</a>
         <a  href="../companies/jobs.php">Jobs</a>
         <a  href="../companies/applications.php">Applications</a>
         <a  href="../companies/test1.php">Tests</a>
         <!-- <a  href="../companies/users_accounts.php">Users</a> -->
         <a  href="../companies/messages.php">Messages</a>
      
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `companies` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['adminname']; ?></p>
         <a href="../companies/update_profile.php" class="btn">Update profile</a>
         <div class="flex-btn">
            <a href="../companies/register_admin.php" class="option-btn">Register</a>
            <a href="../companies/admin_login.php" class="option-btn">Login</a>
         </div>
         <a href="../components/company_logout.php" class="delete-btn" onclick="return confirm('Do you want to logout from the website?');">logout</a> 
      </div>

   </section>
   
</header>
<!-- <Footer style="text-align: center;">
   All rights reserved to Niteesh &copy; 2023
</Footer> -->
</body>
</html>
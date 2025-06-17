<?php
  include '../components/connect.php';

  session_start();

  $admin_id = $_SESSION['admin_id'];

  if(!isset($admin_id)){
    header('location:admin_login.php');
  }

  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="icon" href="../images/ecommerce logo.png">
    
  </head>
  <body>
    <?php include '../components/admin_header.php' ?>

    <section class="orders">

<h1 class="heading">Apllications</h1>

<div class="box-container">
<?php
$a=$_GET['category'];

      $select_orders = $conn->prepare("SELECT * FROM `orders` where company_name = ?");
      $select_orders->execute([$a]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Job name : <span><?= $fetch_orders['job_name']; ?></span> </p>
      <p> Company name : <span><?= $fetch_orders['company_name']; ?></span> </p>
      <p> Description : <span><?= $fetch_orders['details']; ?></span> </p>
      <p> Salary : <span>₹<?= $fetch_orders['salary']; ?>/-</span> </p>
      <p> Location : <span><?= $fetch_orders['location']; ?>/-</span> </p>
      <p>Application status :</p>
      <form action="" method="post">
      <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="verify_status" class="select" default>
            <option selected disabled><?= $fetch_orders['application_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="selected">selected</option>
            <option value="rejected">not selected</option>          </select>
         <div class="flex-btn">
          <input type="submit" value="selected" class="option-btn" name="update_status" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?>
        </div>
         
          <!-- <div class="flex-btn">
          <input type="submit" value="order" class="option-btn" name="update_order" >
          <?php
          if($admin_id==1){
         echo'<a href="placed_orders.php?delete=' . $fetch_orders['id'] . '" class="delete-btn" onclick="return confirm(\'Delete this order?\');">delete</a>';
          }
          ?> -->
        </div>
     
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no applications applied yet!</p>';
      }
   ?>

</div>


</section>

    
    <script src="../js/admin_script.js"></script>   
    <Footer>
      <center><p class="empty " style="margin-top: 15rem;  ">All rights reserved to Niteesh &copy; <?= date('M Y'); ?></p> </center>
  </Footer>
  </body>
  </html>
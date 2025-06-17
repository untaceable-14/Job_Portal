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


    <section class="show-products">

<h1 class="heading">Products added</h1>

<div class="box-container">

<?php
$a=$_GET['category'];
   $show_products = $conn->prepare("SELECT * FROM `products` where company_name = ?");
   $show_products->execute([$a]);
   if($show_products->rowCount() > 0){
      while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){ 
?>
<div class="box">
   <!-- <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt=""> -->
   <div class="name"><?= $fetch_products['job_name']; ?></div>
   <div class="price">₹<span><?= $fetch_products['salary']; ?></span>/-</div>
   <div class="flex-btn">
   <div class="name"><span><?= $fetch_products['company_name']; ?></span></div>
   <div class="name"><span><?= $fetch_products['location']; ?></span></div></div>
   <div class="flex-btn">
      <a href="update_jobs.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
      <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
   </div>
</div>
<?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
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
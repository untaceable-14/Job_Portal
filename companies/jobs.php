
<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:admin_login.php');
}

if(isset($_POST['add_product'])){
  $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
   $skills = $_POST['skills'];
   $skills = filter_var($skills, FILTER_SANITIZE_STRING);
   $comp_name = $_POST['comp_name'];
   $comp_name = filter_var($comp_name, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);

  
    $insert_products = $conn->prepare("INSERT INTO products(job_name, details, salary,required_skills,company_name,location) VALUES(?,?,?,?,?,?)");
    $insert_products->execute([$name, $details, $price, $skills, $comp_name, $location]); 
    $select_products = $conn->prepare("SELECT * FROM `companies` WHERE company_name = ?");
    $select_products->execute([$comp_name]);
 
 
    if($select_products->rowCount() > 0){
      $message[] = 'product added!';
    }else{
    $asd=$conn->prepare("Insert into companies(company_name) values(?)");
    $asd->execute([$comp_name]);
         $message[] = 'new product added!';
    }
      };



if(isset($_GET['delete'])){

  $delete_id = $_GET['delete'];
  $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
  $delete_product_image->execute([$delete_id]);
  $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
  unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
  unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
  unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
  $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
  $delete_product->execute([$delete_id]);
  $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
  $delete_cart->execute([$delete_id]);
  $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
  $delete_wishlist->execute([$delete_id]);
  header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="icon" href="../images/ecommerce logo.png">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
<?php include '../components/company_header.php' ?>
<section class="add-products">
  <h1 class="heading">ADD Jobs</h1>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="flex">
      <div class="inputBox">
        <span>Job Name(Required)</span>
        <input style="border: .2rem solid #444444;" type="text" name="name" class="box" required placeholder="Enter job name" maxlength="200">

      </div>
      <div class="inputBox">
        <span>Details</span>
        <textarea style="height: 5.8rem;resize:vertical; border: .2rem solid #444444;" name="details" class="box" placeholder="Enter job details" required maxlength="5000" cols="30" rows="10"></textarea>
      </div>
      <div class="inputBox">
        <span>Salary(Required)</span>
        <input style="border: .2rem solid #444444;" type="text" name="price" class="box" required placeholder="Enter salary"  min="0" max="9999999999">

      </div>
      
       <div class="inputBox">
        <span>Required Skills</span>
        <input style="border: .2rem solid #444444;" type="text" name="skills" class="box" required placeholder="Enter required skills"  min="0" max="9999999999">
      </div>
      <?php
$select_products = $conn->prepare("SELECT * FROM `companies` where id=?"); 
   $select_products->execute([$admin_id]);
   if($select_products->rowCount() > 0){
      $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)
         ?>
      <div class="inputBox">
        <span>Company Name</span>
        <input style="border: .2rem solid #444444;" type="text" name="comp_name" class="box" required placeholder="Enter company name" value="<?= $fetch_product['company_name']?>"  min="0" max="9999999999">
      </div>
      <?php
    }    
      ?>
      <div class="inputBox">
        <span>Location</span>
        <input style="border: .2rem solid #444444;" type="text" name="location" class="box" required placeholder="Enter location"  min="0" max="9999999999">
      </div>
        <input  type="submit" value="Add JOb" name="add_product" class="btn">

    </div>
  </form>
</section> 












<section class="show-products">

   <h1 class="heading">Jobs added</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `products` where company_name = ?");
      $show_products->execute([$fetch_product['company_name']]);
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
   <center><p class="empty " style="margin-top: 10rem;  ">All rights reserved to Niteesh &copy; 2023</p> </center>
</Footer>
</body>
</html>


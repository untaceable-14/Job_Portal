<?php
// include_once 'components/session_start.php';
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

// include 'components/apply.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/a.css">

   <link rel="icon" href="images/ecommerce logo.png">

</head>
<body>
   
<?php //include 'components/user_header.php'; ?>





<section class="search-form" id="new">
   <form action="" method="post">
      <input type="text" name="search_box" placeholder="Search for Jobs and Companies" maxlength="100" id="" class="box search-box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>

<?php
if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
echo'<h1 class="heading">Search results...</h1>';
}?>
<section class="products" id="new">
   
   <?php
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
        $search_box = $_POST['search_box'];
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE job_name LIKE '%{$search_box}%' or company_name LIKE '%{$search_box}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
        while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
           ?>
           <div class="box-container">

   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['company_name']; ?>">
      <div class="name" style="padding: 0.5rem;"><center><?= $fetch_product['job_name']; ?></center></div>
      <div class="flex">
         <div class="price" style="padding: 0.5rem;"><span class="name">Salary :- </span><span>₹</span><?= $fetch_product['salary']; ?><span>/-</span></div>
      </div>
      <div class="flex">
         <div class="name" style="padding: 0.5rem;">Location :- <span class="price"><?= $fetch_product['location']; ?></span></div></div>
         <div class="flex">
            <div class="name" style="padding: 0.5rem;">Company :- <span class="price"><?= $fetch_product['company_name']; ?></span></div></div>
            <div class="flex-btn">
            <a href="quick_view2.php?pid=<?= $fetch_product['id']; ?>" class="btn">view job</a>
            <a href="apply.php?pid=<?= $fetch_product['id']; ?>" class="btn" name="apply_now_job">apply </a></div>
            <a href="" class="btn">Clear</a>
            </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Nothing found similar to your search!</p> ';
      }
   }
   ?>
   </div>

</section>





<?php 
// include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>


</body>
</html>
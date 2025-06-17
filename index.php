<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};
include 'components/apply.php'; 

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

   <style>
  @media (max-width: 768px) {
    .home {
      flex-direction: column;
      text-align: center;
    }

    .left, .right {
      flex: 1 1 100%;
    }

    .home-text h1 {
      font-size: 2.4rem;
    }

    .home-text p {
      font-size: 1.3rem;
    }

    .left .heading1 {
      font-size: 1.9rem;
    }

    .left .heading2 {
      font-size: 2.4rem;
    }
  }

/* Responsive design */
@media (max-width: 768px) {
  .home {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 20px;
  }

  .left, .right {
    width: 100%;
  }

  .right img {
    max-width: 100%;
    height: auto;
  }

  .heading1 {
    font-size: 1.9rem;
  }

  .heading2 {
    font-size: 2.4rem;
  }

  .heading3 {
    font-size: 1.4rem;
  }

  .btn-home {
    padding: 10px 20px;
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .heading1 {
    font-size: 1.2rem;
  }

  .heading2 {
    font-size: 1.5rem;
  }

  .heading3 {
    font-size: 0.9rem;
  }

  .btn-home {
    padding: 8px 16px;
    font-size: 0.9rem;
  }
}



</style>



</head>
  
<body>
   
<?php include 'components/user_header.php'; ?>

<?php include 'search_page.php'; ?>
<!-- <script>
   document.getElementById("search_btn").addEventListener("click", function() {
      document.getElementById("new").classList.toggle("activate");
      
      });
   </script> -->
   <div class="home-text">
      <!-- <center> -->
         <h1>For every hard effort,<br> there is help to achieve better opportunities.</h1>
         <p>Reach your work better, Your success starts from now, Lets find and lets work</p>
      <!-- </center> -->
   </div>




<section class="home">
   <div class="flex">
      <!-- <center> -->
      <div class="left">
         <p class="heading1">#1 Platform for job seekers 🔥</p>
         <h1 class="heading1"><b>New offers <br> are awaiting for you👍</b></h1>
         <p class="heading3">Find your dream job with us, we are here to help you <a href="jobs.php"><button class="btn-home">Explore now</button></a></p>
         
      </div>
   </div>
      
      <div class="right">
         <img src="images/pixelcut-export.png" width="320px" height="300px" alt="">
      </div>
   
</section>





<!-- </center> -->

<section class="category" style="height: fit-content;">

   <h1 class="heading">Popular categories</h1>

   <div class="swiper category-slider">
   
   <div class="swiper-wrapper">

   <a href="category.php?category=Software developer" class="swiper-slide slide">
      <h3>Software Developer</h3>
   </a>
   
 <a href="category.php?category=devoops enginneer" class="swiper-slide slide">
      <h3>DevOops Engineer</h3>
   </a>
   <a href="category.php?category=data analyst" class="swiper-slide slide">
      <h3>Data Analytics </h3>
   </a>

   <a href="category.php?category=systems management" class="swiper-slide slide">
      <h3>Systems Management</h3>
   </a>

   

  </div>
   <div class="swiper-pagination"></div>

</div>

   <div class="swiper category-slider">
   
   <div class="swiper-wrapper">
  

   <a href="category.php?category=marketing manager" class="swiper-slide slide">
      <h3>Marketing Manager</h3>
   </a>
   
   <a href="category.php?category=social media handler" class="swiper-slide slide">
      <h3>Social Media Handler</h3>
   </a>
   <a href="category.php?category=informaation security analyst" class="swiper-slide slide">
      <h3>Security Analyst</h3>
   </a> 
    
   <a href="category.php?category=web developer" class="swiper-slide slide">
      <h3>Web Developer</h3>
   </a>
   
   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products" style="height: fit-content;">

   <h1 class="heading">Latest Jobs</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['company_name']; ?>">
      <!-- <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> -->
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <!-- <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt=""> -->
      <div class="name"><center><?= $fetch_product['job_name']; ?></center></div>
      <div class="flex">
      <div class="price"><span class="name">Salary :- </span><span>₹</span><?= $fetch_product['salary']; ?><span>/-</span></div>
   </div>
   <div class="flex">
   <div class="name">Location :- <span class="price"><?= $fetch_product['location']; ?></span></div></div>
      <!-- <input type="submit" value="apply" class="btn" name="apply_now_job"> -->
      <a href="apply.php?pid=<?= $fetch_product['id']; ?>" class="btn" name="apply_now_job">apply now</a>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no jobs posted yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>



<?php include 'components/user_footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>

<script>

document.getElementById("user-btn").addEventListener("click", function() {
   document.getElementById("new1").classList.toggle("activate");
  
});
</script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   // grabCursor:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 10,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
     breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 4,
      },
   },
});
var swiper = new Swiper(".mySwiper", {
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   }, 
    breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>
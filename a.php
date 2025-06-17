<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}

if (isset($_POST['order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $job_name = filter_var($_POST['job_name'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
    $salary = filter_var($_POST['salary'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $expected_salary = filter_var($_POST['salary'], FILTER_SANITIZE_STRING);
    $start_date = $_POST['start_date'];
    $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
    $specific = filter_var($_POST['specific'], FILTER_SANITIZE_STRING);
    $university = filter_var($_POST['university'], FILTER_SANITIZE_STRING);
    $graduation_year = filter_var($_POST['graduation_year'], FILTER_SANITIZE_STRING);
    $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);
    $previous_company = filter_var($_POST['previous_company'], FILTER_SANITIZE_STRING);
    // $job_title = filter_var($_POST['job_title'], FILTER_SANITIZE_STRING);
    $skills = filter_var($_POST['skills'], FILTER_SANITIZE_STRING);
    $certifications = filter_var($_POST['certifications'], FILTER_SANITIZE_STRING);
    $portfolio = filter_var($_POST['portfolio'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
    $worked_before = filter_var($_POST['worked_before'], FILTER_SANITIZE_STRING);
    $eligible = filter_var($_POST['eligible'], FILTER_SANITIZE_STRING);

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() >= 0) {
        $insert_order = $conn->prepare("INSERT INTO `orders` 
            (user_id, name, number, email, company_name, job_name, details, salary, location, phone, 
            expected_salary, start_date, qualification, `specific`, university, graduation_year, 
            experience, previous_company, skills, certifications, portfolio, reason, 
            worked_before, eligible, application_status) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $insert_order->execute([$user_id, $name, $number, $email, $company_name, $job_name, $details, 
            $salary, $location, $phone, $expected_salary, $start_date, $qualification, $specific, 
            $university, $graduation_year, $experience, $previous_company, $skills, 
            $certifications, $portfolio, $reason, $worked_before, $eligible, 'pending']);

        $message[] = 'Application Submitted';
    } else {
        $message[] = 'Your cart is empty';
    }
}

include 'components/apply.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Apply</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/user_style.css">

   <link rel="icon" href="ecommerce logo.png">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<?php include 'search_page.php'; ?>



<!-- not yet completed -->







<section class="checkout-orders">
   <form action="" method="post">
   <div class="display-orders">
   <h3>Apply for the job</h3>
   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
         <p> <?= $fetch_product['job_name']; ?> </p>
    

      <h3>Fill the details</h3>

      <div class="flex">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="job_name" value="<?= $fetch_product['job_name']; ?>">
      <input type="hidden" name="details" value="<?= $fetch_product['details']; ?>">
      <input type="hidden" name="salary" value="<?= $fetch_product['salary']; ?>">
      <input type="hidden" name="location" value="<?= $fetch_product['location']; ?>">
      <input type="hidden" name="company_name" value="<?= $fetch_product['company_name']; ?>">
      <?php
            }
         }else{
            echo '<p class="empty">your application is empty!</p>';
         }
      ?>
      <?php
      $select_products1 = $conn->prepare("SELECT * FROM `orders` WHERE id = ?"); 
     $select_products1->execute([$user_id]);
     $fetch_product1 = $select_products1->fetch(PDO::FETCH_ASSOC);
      ?>

<div class="inputBox">
            <span>Your name : <span style="color:red;">*</span></span>
            <input type="text" name="name" placeholder="enter your name" maxlength="20" class="box" 
       value="<?= !empty($fetch_product1['name']) ? $fetch_product1['name'] : ($_POST['name'] ?? ''); ?>" 
       <?= !empty($fetch_product1['name']) ? 'readonly' : ''; ?> required>


         </div>
         <div class="inputBox">
            <span>Your number :<span style="color:red;">*</span></span>
            <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Your email :<span style="color:red;">*</span></span>
            <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>  
      </div>
     
    <div class="inputBox">
        <span>Phone Number:<span style="color:red;">*</span></span>
        <input type="tel" name="phone" class="box" placeholder="Enter your phone number" required>
    </div>

    <div class="inputBox">
        <span>Expected Salary:<span style="color:red;">*</span></span>
        <input type="number" name="salary" class="box" placeholder="Enter expected salary">
    </div>

    <div class="inputBox">
        <span>Availability to Start:<span style="color:red;">*</span></span>
        <input type="date" name="start_date" class="box">
    </div>

    <!-- Education & Experience -->
    <div class="inputBox">
        <span>Highest Qualification:<span style="color:red;">*</span></span>
        <select name="qualification" class="box" required>
            <option value="">Select Qualification</option>
            <option value="Bachelor's">Bachelor's</option>
            <option value="Master's">Master's</option>
            <option value="Diploma">Diploma</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="inputBox">
        <span>Specification :<span style="color:red;">*</span></span>
        <input type="text" name="specific" class="box" placeholder="Enter course specification ">
    </div>


    <div class="inputBox">
        <span>University/College Name:<span style="color:red;">*</span></span>
        <input type="text" name="university" class="box" placeholder="Enter university name">
    </div>

    <div class="inputBox">
        <span>Year of Graduation:<span style="color:red;">*</span></span>
        <input type="number" name="graduation_year" class="box" placeholder="Enter graduation year" min="1900" max="2099">
    </div>

    <div class="inputBox">
        <span>Total Work Experience (Years):<span style="color:red;">*</span></span>
        <input type="number" name="experience" class="box" placeholder="Enter years of experience" min="0">
    </div>

    <div class="inputBox">
        <span>Previous Company:<span style="color:red;">*</span></span>
        <input type="text" name="previous_company" class="box" placeholder="Enter previous company name">
    </div>

   
    <!-- Skills & Certifications -->
    <div class="inputBox">
        <span>Technical Skills:<span style="color:red;">*</span></span>
        <input type="text" name="skills" class="box" placeholder="e.g., Linux, AWS, PHP" required>
    </div>

    <div class="inputBox">
        <span>Certifications:</span>
        <input type="text" name="certifications" class="box" placeholder="Enter certifications">
    </div>

    <!-- Resume Upload -->
    
    <div class="inputBox">
        <span>Portfolio Link:</span>
        <input type="url" name="portfolio" class="box" value="" placeholder="Enter portfolio link">
    </div>

    <!-- Additional Questions -->
    <div class="inputBox">
        <span>Why do you want to work with us?<span style="color:red;">*</span></span>
        <input name="reason" class="box" placeholder="Write your answer here" rows="3"></input>
    </div>

    <div class="inputBox">
        <span>Have you worked with us before?<span style="color:red;">*</span></span>
        <select name="worked_before" class="box">
            <option value="No">No</option>
            <option value="Yes">Yes</option>
        </select>
    </div>

    <div class="inputBox">
        <span>Are you legally eligible to work?<span style="color:red;">*</span></span>
        <select name="eligible" class="box">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <!-- Terms & Conditions -->
   
    <div class="inputBox">
        <span><input type="checkbox" name="agree" required> I agree to the Terms & Privacy Policy<span style="color:red;">*</span></span>
    </div>

   

         <!-- <div class="inputBox">
            <span>Payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div> 
         <div class="inputBox">
            <span>Address :</span>
            <input type="text" name="address" placeholder="e.g. flat number" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" maxlength="50" required>
         </div> 
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" placeholder="e.g.ponneri" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>State :</span>
            <input type="text" name="state" placeholder="e.g. Tamil nadu" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Country :</span>
            <input type="text" name="country" placeholder="e.g. India" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>-->

      <input type="submit" name="order" class="btn" value="apply">

      </form>
</section>


<?php 
include 'components/user_footer.php';

?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/user_script.js"></script>

</body>
</html>
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
    $pid= $_POST['pid'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $job_name = filter_var($_POST['job_name'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
    $salary = filter_var($_POST['salary'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $expected_salary = filter_var($_POST['expected_salary'], FILTER_SANITIZE_STRING);
    $start_date = $_POST['start_date'];
    $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
    $specific = filter_var($_POST['specific'], FILTER_SANITIZE_STRING);
    $university = filter_var($_POST['university'], FILTER_SANITIZE_STRING);
    $graduation_year = filter_var($_POST['graduation_year'], FILTER_SANITIZE_STRING);
    $experience = filter_var($_POST['experience'], FILTER_SANITIZE_STRING);
    $previous_company = filter_var($_POST['previous_company'], FILTER_SANITIZE_STRING);
    $skills = filter_var($_POST['skills'], FILTER_SANITIZE_STRING);
    $certifications = filter_var($_POST['certifications'], FILTER_SANITIZE_STRING);
    $portfolio = filter_var($_POST['portfolio'], FILTER_SANITIZE_STRING);
    $reason = filter_var($_POST['reason'], FILTER_SANITIZE_STRING);
    $worked_before = filter_var($_POST['worked_before'], FILTER_SANITIZE_STRING);
    $eligible = filter_var($_POST['eligible'], FILTER_SANITIZE_STRING);

    $check_existing = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND company_name = ? AND job_name = ? AND salary = ? AND location = ?"); 
    $check_existing->execute([$user_id, $company_name, $job_name, $salary, $location]);

    if ($check_existing->rowCount() > 0) {
        // 🚫 Prevent duplicate application
        $message[] = 'Already applied for this job at this company';
    } else {
        // ✅ Allow new application
        $insert_order = $conn->prepare("INSERT INTO `orders` 
            (user_id, job_id, name, number, email, company_name, job_name, details, salary, location, expected_salary, start_date, qualification, `specific`, university, graduation_year, 
            experience, previous_company, skills, certifications, portfolio, reason, 
            worked_before, eligible, application_status) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $insert_order->execute([$user_id, $pid, $name, $number, $email, $company_name, $job_name, $details, 
            $salary, $location, $expected_salary, $start_date, $qualification, $specific, 
            $university, $graduation_year, $experience, $previous_company, $skills, 
            $certifications, $portfolio, $reason, $worked_before, $eligible, 'pending']);

        $message[] = 'Application Submitted';
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
      $select_products1 = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?"); 
     $select_products1->execute([$user_id]);
     $fetch_product1 = $select_products1->fetch(PDO::FETCH_ASSOC);
      ?>





<div class="inputBox">
    <span>Your Name: <span style="color:red;">*</span></span>
    <input type="text" id="name" name="name" placeholder="Enter your name" maxlength="20" class="box" 
        value="<?= !empty($fetch_product1['name']) ? htmlspecialchars($fetch_product1['name']) : ($_POST['name'] ?? ''); ?>" 
        <?= !empty($fetch_product1['name']) ? 'readonly' : ''; ?> required>

    <?php if (!empty($fetch_product1['name'])): ?>
        <button type="button" onclick="enableField('name')">Edit</button>
    <?php endif; ?>
</div>


       
       
       
<div class="inputBox">
    <span>Your Number: <span style="color:red;">*</span></span>
    <input type="number" id="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999"
           value="<?= !empty($fetch_product1['number']) ? $fetch_product1['number'] : ($_POST['number'] ?? ''); ?>"
           onkeypress="if(this.value.length == 10) return false;"
           <?= !empty($fetch_product1['number']) ? 'readonly' : ''; ?> required>

    <?php if (!empty($fetch_product1['number'])): ?>
        <button type="button" onclick="enableField('number')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Your Email: <span style="color:red;">*</span></span>
    <input type="email" id="email" name="email" placeholder="Enter your email" class="box" maxlength="50"
           value="<?= !empty($fetch_product1['email']) ? $fetch_product1['email'] : ($_POST['email'] ?? ''); ?>"
           <?= !empty($fetch_product1['email']) ? 'readonly' : ''; ?> required>

    <?php if (!empty($fetch_product1['email'])): ?>
        <button type="button" onclick="enableField('email')">Edit</button>
    <?php endif; ?>
</div>







<div class="inputBox">
    <span>Expected Salary: <span style="color:red;">*</span></span>
    <input type="number" id="expected_salary" name="expected_salary" class="box" placeholder="Enter expected salary"
           value="<?= !empty($fetch_product1['expected_salary']) ? $fetch_product1['expected_salary'] : ($_POST['expected_salary'] ?? ''); ?>"
           <?= !empty($fetch_product1['expected_salary']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['expected_salary'])): ?>
        <button type="button" onclick="enableField('expected_salary')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Availability to Start: <span style="color:red;">*</span></span>
    <input type="date" id="start_date" name="start_date" class="box"
           value="<?= !empty($fetch_product1['start_date']) ? $fetch_product1['start_date'] : ($_POST['start_date'] ?? ''); ?>"
           <?= !empty($fetch_product1['start_date']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['start_date'])): ?>
        <button type="button" onclick="enableField('start_date')">Edit</button>
    <?php endif; ?>
</div>



<div class="inputBox">
    <span>Highest Qualification:<span style="color:red;">*</span></span>
    <select id="qualification" name="qualification" class="box" required
        <?php if (!empty($fetch_product1['qualification'])) echo 'disabled'; ?>
        onchange="updateHiddenField('qualification')">
        <?php 
            $selected_qualification = $fetch_product1['qualification'] ?? $_POST['qualification'] ?? '';
        ?>
        <?php if (!empty($selected_qualification)): ?>
            <option value="<?= htmlspecialchars($selected_qualification); ?>" selected><?= htmlspecialchars($selected_qualification); ?></option>
        <?php endif; ?>
        <option value="empty">-- --</option>
        <option value="Bachelor's" <?= ($selected_qualification == "Bachelor's") ? 'selected' : ''; ?>>Bachelor's</option>
        <option value="Master's" <?= ($selected_qualification == "Master's") ? 'selected' : ''; ?>>Master's</option>
        <option value="Diploma" <?= ($selected_qualification == "Diploma") ? 'selected' : ''; ?>>Diploma</option>
        <option value="Other" <?= ($selected_qualification == "Other") ? 'selected' : ''; ?>>Other</option>
    </select>

    <?php if (!empty($fetch_product1['qualification'])): ?>
        <button type="button" onclick="enableField('qualification')">Edit</button>
    <?php endif; ?>

    <!-- Hidden input to ensure value is always submitted -->
    <input type="hidden" id="hidden_qualification" name="qualification" value="<?= htmlspecialchars($selected_qualification); ?>">
</div>

<script>
    function enableField(id) {
        document.getElementById(id).disabled = false;
    }

    function updateHiddenField(id) {
        document.getElementById("hidden_" + id).value = document.getElementById(id).value;
    }
</script>










<div class="inputBox">
    <span>Specification:<span style="color:red;">*</span></span>
    <input type="text" name="specific" id="specific" class="box" placeholder="Enter course specification"
           value="<?= !empty($fetch_product1['specific']) ? htmlspecialchars($fetch_product1['specific']) : ($_POST['specific'] ?? ''); ?>"
           <?= !empty($fetch_product1['specific']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['specific'])): ?>
        <button type="button" onclick="enableField('specific')">Edit</button>
    <?php endif; ?>
</div>







<div class="inputBox">
    <span>University/College Name: <span style="color:red;">*</span></span>
    <input type="text" id="university" name="university" class="box" placeholder="Enter university name"
           value="<?= !empty($fetch_product1['university']) ? $fetch_product1['university'] : ($_POST['university'] ?? ''); ?>"
           <?= !empty($fetch_product1['university']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['university'])): ?>
        <button type="button" onclick="enableField('university')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Year of Graduation:<span style="color:red;">*</span></span>
    <input type="number" name="graduation_year" id="graduation_year" class="box" placeholder="Enter graduation year" min="1900" max="2099"
           value="<?= !empty($fetch_product1['graduation_year']) ? htmlspecialchars($fetch_product1['graduation_year']) : ($_POST['graduation_year'] ?? ''); ?>"
           <?= !empty($fetch_product1['graduation_year']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['graduation_year'])): ?>
        <button type="button" onclick="enableField('graduation_year')">Edit</button>
    <?php endif; ?>
</div>







<div class="inputBox">
    <span>Total Work Experience (Years): <span style="color:red;">*</span></span>
    <input type="number" id="experience" name="experience" class="box" placeholder="Enter years of experience" min="0"
           value="<?= !empty($fetch_product1['experience']) ? $fetch_product1['experience'] : ($_POST['experience'] ?? ''); ?>"
           <?= !empty($fetch_product1['experience']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['experience'])): ?>
        <button type="button" onclick="enableField('experience')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Previous Company: <span style="color:red;">*</span></span>
    <input type="text" id="previous_company" name="previous_company" class="box" placeholder="Enter previous company name"
           value="<?= !empty($fetch_product1['previous_company']) ? $fetch_product1['previous_company'] : ($_POST['previous_company'] ?? ''); ?>"
           <?= !empty($fetch_product1['previous_company']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['previous_company'])): ?>
        <button type="button" onclick="enableField('previous_company')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Technical Skills: <span style="color:red;">*</span></span>
    <input type="text" id="skills" name="skills" class="box" placeholder="e.g., Linux, AWS, PHP"
           value="<?= !empty($fetch_product1['skills']) ? $fetch_product1['skills'] : ($_POST['skills'] ?? ''); ?>"
           <?= !empty($fetch_product1['skills']) ? 'readonly' : ''; ?> required>

    <?php if (!empty($fetch_product1['skills'])): ?>
        <button type="button" onclick="enableField('skills')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Certifications:</span>
    <input type="text" name="certifications" id="certifications" class="box" placeholder="Enter certifications"
           value="<?= !empty($fetch_product1['certifications']) ? htmlspecialchars($fetch_product1['certifications']) : ($_POST['certifications'] ?? ''); ?>"
           <?= !empty($fetch_product1['certifications']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['certifications'])): ?>
        <button type="button" onclick="enableField('certifications')">Edit</button>
    <?php endif; ?>
</div>







<div class="inputBox">
    <span>Portfolio Link:</span>
    <input type="url" id="portfolio" name="portfolio" class="box" placeholder="Enter portfolio link"
           value="<?= !empty($fetch_product1['portfolio']) ? $fetch_product1['portfolio'] : ($_POST['portfolio'] ?? ''); ?>"
           <?= !empty($fetch_product1['portfolio']) ? 'readonly' : ''; ?>>

    <?php if (!empty($fetch_product1['portfolio'])): ?>
        <button type="button" onclick="enableField('portfolio')">Edit</button>
    <?php endif; ?>
</div>






<div class="inputBox">
    <span>Why do you want to work with us?<span style="color:red;">*</span></span>
    <input name="reason" id="reason" class="box" placeholder="Write your answer here"
           value="<?= !empty($fetch_product1['reason']) ? htmlspecialchars($fetch_product1['reason']) : ($_POST['reason'] ?? ''); ?>"
           <?= !empty($fetch_product1['reason']) ? 'readonly' : ''; ?> max="500" required>

    <?php if (!empty($fetch_product1['reason'])): ?>
        <button type="button" onclick="enableField('reason')">Edit</button>
    <?php endif; ?>
</div>


<div class="inputBox">
    <span>Have you worked with us before?<span style="color:red;">*</span></span>
    <select id="worked_before" name="worked_before" class="box" required
        <?php if (!empty($fetch_product1['worked_before'])) echo 'disabled'; ?>
        onchange="updateHiddenField('worked_before')">
        <?php 
            $selected_worked_before = $fetch_product1['worked_before'] ?? $_POST['worked_before'] ?? '';
        ?>
        <option value="empty">-- --</option>
        <option value="No" <?= ($selected_worked_before == "No") ? "selected" : ""; ?>>No</option>
        <option value="Yes" <?= ($selected_worked_before == "Yes") ? "selected" : ""; ?>>Yes</option>
    </select>

    <?php if (!empty($fetch_product1['worked_before'])): ?>
        <button type="button" onclick="enableField('worked_before')">Edit</button>
    <?php endif; ?>

    <!-- Hidden input ensures submission if disabled -->
    <input type="hidden" id="hidden_worked_before" name="worked_before" value="<?= htmlspecialchars($selected_worked_before); ?>">
</div>

<!-- Are you legally eligible to work? -->
<div class="inputBox">
    <span>Are you legally eligible to work?<span style="color:red;">*</span></span>
    <select id="eligible" name="eligible" class="box" required
        <?php if (!empty($fetch_product1['eligible'])) echo 'disabled'; ?>
        onchange="updateHiddenField('eligible')">
        <?php 
            $selected_eligible = $fetch_product1['eligible'] ?? $_POST['eligible'] ?? '';
        ?>
        <option value="empty">-- --</option>
        <option value="Yes" <?= ($selected_eligible == "Yes") ? "selected" : ""; ?>>Yes</option>
        <option value="No" <?= ($selected_eligible == "No") ? "selected" : ""; ?>>No</option>
    </select>

    <?php if (!empty($fetch_product1['eligible'])): ?>
        <button type="button" onclick="enableField('eligible')">Edit</button>
    <?php endif; ?>

    <!-- Hidden input ensures submission if disabled -->
    <input type="hidden" id="hidden_eligible" name="eligible" value="<?= htmlspecialchars($selected_eligible); ?>">
</div>

<script>
    function enableField(id) {
        document.getElementById(id).disabled = false;
    }

    function updateHiddenField(id) {
        document.getElementById("hidden_" + id).value = document.getElementById(id).value;
    }
</script>

<!-- Terms & Conditions -->
<div class="inputBox">
    <span>
        <input type="checkbox" id="agree" name="agree" required 
            <?php if (!empty($fetch_product1['agree'])) echo 'disabled checked'; ?>>

        I agree to the Terms & Privacy Policy<span style="color:red;">*</span>

        <?php if (!empty($fetch_product1['agree'])): ?>
            <button type="button" onclick="enableField('agree')">Edit</button>
        <?php endif; ?>

        <!-- Hidden input ensures submission if the field is disabled -->
        <input type="hidden" id="hidden_agree" name="agree" value="on">
    </span>
</div>

<script>
    function enableField(id) {
        let field = document.getElementById(id);
        let hiddenField = document.getElementById("hidden_" + id);

        field.disabled = false; // Enable the field
        if (hiddenField) {
            hiddenField.disabled = true; // Disable the hidden input when the field is enabled
        }
    }
</script>
<script>
    function enableField(id) {
        document.getElementById(id).readOnly = false;
    }
</script>



   

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
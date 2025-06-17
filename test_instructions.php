<?php 

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>
<?php
    if(isset($_SESSION['pop-up'])){
      $message[] = 'Logged in successfully';
      unset($_SESSION['pop-up']);
}?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Test Instructions</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/user_style.css">
</head>

<body>
   <center>
<section class="orders">
<?php
$select_products = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
$select_products->execute([$user_id]);
$fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
?>

<div class="box-container">
<div class="box">

    <div class="flex">
        <div class="name" style="font-size: 20px; border: 1px solid black; padding: 10px; margin: 10px; text-align: left;">
            <b class="heading">✅ Instructions for Using the Face & Eye Detection Proctoring System</b><br><br>

            <b><h2>📋 DO’s:</h2></b><br>
            <b>1. Ensure Proper Lighting:</b><br> Sit in a <b>well-lit environment</b> so your face and eyes are clearly visible to the camera.<br><br>
            <b>2. Face the Screen Directly:</b><br> Keep your face <b>centered</b> and <b>straight</b> towards the camera at all times.<br><br>
            <b>3. Maintain Eye Contact:</b><br> Look <b>directly at the screen</b>. Avoid looking away to the sides.<br><br>
            <b>4. Use a Single Camera:</b><br> Ensure <b>only one camera</b> is connected and active to avoid conflicts.<br><br>
            <b>5. Stay in Full-Screen Mode:</b><br> The test will <b>auto full-screen</b> when it starts. <b>Do not exit full-screen mode</b> — doing so will <b>immediately end the session</b>.<br><br>
            <b>6. Be Alone:</b><br> Make sure <b>no one else is visible</b> in the frame. <b>Multiple faces</b> will trigger warnings.<br><br>
            <b>7. Be Quiet:</b><br> Ensure a <b>quiet environment</b>. Unnecessary sounds will trigger warnings.<br><br>
            <b>8. Enter Fullscreen Mode:</b><br> Enter fullscreen mode on your PC, by clicking F11.<br><br>
            

            <b><h2>🚫 DON’Ts:</h2></b><br>
            <b>1. Don’t Look Away:</b><br> Looking <b>left or right</b> for too long will count as a <b>violation</b>.<br><br>
            <b>2. Don’t Exit Full-Screen:</b><br> If you <b>exit full-screen mode</b>, the test will <b>automatically terminate</b>.<br><br>
            <b>3. No Multiple Faces:</b><br> Ensure <b>no one else appears</b> in the frame.<br><br>
            <b>4. Avoid Face Absence:</b><br> If your face <b>disappears for more than 3 seconds</b>, you'll receive <b>up to 3 alerts</b>.<br><br>
            <b>5. No Sudden Movements:</b><br> Don’t <b>tilt your head excessively</b> or move <b>out of the frame</b>.<br><br>
            <b><h2>Requirements</h2></b><br>
            <b>1. Proper Internet Connectio:</b><br> Ensure you have a <b>stable internet connection</b> throughout the test.<br><br>
            <b>2. Webcam:</b><br> Make sure your <b>webcam is working</b> and <b>properly connected</b>.<br><br>
            <b>3. Microphone:</b><br> Ensure your <b>microphone is working</b> and <b>properly connected</b>.<br><br>
            <input type="checkbox" name="agree" id="agree" required> I have read and understood the instructions<br><br>
            <div class="name"><p>Enter full screen mode by clicking f11</p></div>

            <div class="flex-btn">
                <a href="exam.php?a=<?= $fetch_product['company_name']; ?>" class="btn" id="take-test-btn" style="pointer-events: none; opacity: 0.5;">Take Test</a>
            </div>
        </div>
    </div>
</div>
</div>
</section>
</center>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("agree");
    const takeTestBtn = document.getElementById("take-test-btn");

    function checkConditions() {
        const isFullScreen = document.fullscreenElement || 
                             document.mozFullScreenElement || 
                             document.webkitFullscreenElement || 
                             document.msFullscreenElement;

        if (checkbox.checked && isFullScreen) {
            takeTestBtn.style.pointerEvents = "auto";
            takeTestBtn.style.opacity = "1";
        } else {
            takeTestBtn.style.pointerEvents = "none";
            takeTestBtn.style.opacity = "0.5";
        }
    }

    checkbox.addEventListener("change", checkConditions);

    document.addEventListener("fullscreenchange", checkConditions);
    document.addEventListener("mozfullscreenchange", checkConditions);
    document.addEventListener("webkitfullscreenchange", checkConditions);
    document.addEventListener("msfullscreenchange", checkConditions);

    // Enter fullscreen on checkbox click
    checkbox.addEventListener("click", function () {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.error("Failed to enter fullscreen:", err);
            });
        }
    });
});

</script>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>

<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: user_login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$a = $_GET['a']; // Company name

// Fetch company details
$select_company = $conn->prepare("SELECT * FROM `companies`");
$select_company->execute();
$fetch_company = $select_company->fetch(PDO::FETCH_ASSOC);

// Fetch questions and answers for the test
$select_mcq = $conn->prepare("SELECT * FROM `mcq` WHERE company_name = ?");
$select_mcq->execute([$a]);
$mcq_data = $select_mcq->fetch(PDO::FETCH_ASSOC);

if ($mcq_data) {
    $questions = json_decode($mcq_data['questions'], true);
    $answers = json_decode($mcq_data['answers'], true);
}

// Check if user has already completed this test
$test_id = $mcq_data['id'];
$check_existing = $conn->prepare("SELECT * FROM `test_results` WHERE user_id = ? AND test_id = ?");
$check_existing->execute([$user_id, $test_id]);
$test_completed = $check_existing->fetch(PDO::FETCH_ASSOC);

// Check the display column status
$show_results = ($test_completed && $test_completed['display'] === 'show');

if (isset($_POST['submit_test']) && !$test_completed) {
    $user_answers = $_POST['answers'];
    $correct_answers = 0;

    foreach ($answers as $index => $correct_answer) {
        $user_answer = $user_answers[$index] ?? '';
        if (strtolower(trim($user_answer)) === strtolower(trim($correct_answer))) {
            $correct_answers++;
        }
    }

    $score = ($correct_answers / count($answers)) * 100;
    $answers_json = json_encode($user_answers);

    $insert_result = $conn->prepare("INSERT INTO `test_results` (user_id, test_id, company_name, answers, score, display) VALUES (?, ?, ?, ?, ?, 'hide')");
    $insert_result->execute([$user_id, $test_id, $a, $answers_json, $score]);

    // header('location: results.php');
    $message[] = 'Test submitted successfully!';
   if ($test_completed):
        echo'<h2>You have already completed this test.</h2>';
         endif; 

}
?>









<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take the Test</title>
   <link rel="stylesheet" href="css/user_style.css">

    <style>
       /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    text-align: center;
    margin: 0;
    padding: 0;
}

/* Video Container */
#video-container {
    position: relative;
    display: inline-block;
}

/* Video and Canvas Styling */
video {
    border: 2px solid black;
    width: 440px;
    height: 280px;
}
.asdqw{
    position: fixed;
    /* right: 100%;    */
}
canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    
}

/* Face & Eye Detection UI */
#face-count {
    font-size: 24px;
    font-weight: bold;
    color: red;
    margin-top: 10px;
}

#eye-direction {
    font-size: 22px;
    font-weight: bold;
    color: blue;
    margin-top: 10px;
}

/* Container for Additional Sections */
.container {
    right: 100%;
    margin-left: 50%;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Question & Form Section */
.question-section {
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background: #f1f1f1;
}

/* Form Elements */
label {
    font-size: 16px;
    font-weight: bold;
    display: block;
    margin-top: 5px;
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button Styling */
button {
    margin-top: 10px;
    padding: 10px 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background: #0056b3;
}
#start-test-btn {
        margin: 20px;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    #start-test-btn:hover {
        background: #0056b3;
    }
    .hidden {
        display: none;
    }

    </style>
</head>
<body>

<?php //include 'components/user_header.php'; ?>
<?php //include 'search_page.php'; ?>
<section class="orders">
    <h1 class="heading">take the test</h1>
    <button id="start-test-btn">Start Test</button>
    <div id="warning-message" style="
    display: none;
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(255, 0, 0, 0.8);
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    font-size: 18px;
    z-index: 9999;">
</div>
<div class="flex-btn">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startTestBtn = document.getElementById("start-test-btn");
        const testContent = document.getElementById("test-content");
        const proctorVideo = document.getElementById("proctor-video");

        // Start Test Button Click
        startTestBtn.addEventListener("click", function () {
            // Enter fullscreen mode
            enterFullScreen();

            // Show MCQ and Proctor Video
            proctorVideo.classList.remove("hidden");

            // Hide Start Test button
            startTestBtn.classList.add("hidden");
        });

        // Enter Fullscreen Mode
        function enterFullScreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }
    });
</script> 


<div class="asdqw">
<?php if ($test_completed){

}else{
    ?>
    
<h1>Eye Movement Detection</h1>
    <div id="video-container">
        <video id="video" autoplay playsinline></video>
        <canvas id="canvas"></canvas>
    </div>
    <div id="face-count">Faces: 0</div>
    <div id="eye-direction">Eye Direction: Unknown</div>
    <div id="warning-message" style="color: red; display: none;"></div>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-core"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-tfjs-converter"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-tfjs-backend-webgl"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.9.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface@0.0.7"></script>

     <?php
}
?>    
</div> 

<div class="container">
<div id="proctor-video" class="hidden">
    <?php if ($test_completed): ?>
       <center> <h2>You have already completed this test, <br>The results will be published soon</h2>
        <?php if ($show_results): ?>
            <a href="results.php" class="btn">View Results</a>
            <?php endif; ?>
        <a href="applications.php" class="delete-btn">Exit</a></center>
            <?php else: ?>
                <form method="POST" autocomplete="off">
            <!-- <h1 class="heading">Take the Test</h1> -->
            <?php if ($mcq_data): ?>
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-section">
                        <label for="question<?= $index; ?>"><?= "Q" . ($index + 1) . ": " . htmlspecialchars($question); ?></label>
                        <input type="text" name="answers[<?= $index; ?>]" required>
                    </div>
                <?php endforeach; ?>
               <div class="flex-btn"> <input type="submit" class="btn" name="submit_test"></input>
                <a href="applications.php" class="delete-btn">Exit</a></div>
            <?php else: ?>
                <p>No questions found for your company. Please contact the admin.</p>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div></div><br><br><br><br>


</div>
</div>
</section>
<?php //include 'components/user_footer.php'; ?>
<script src="script.js"></script> 

</body>
</html>
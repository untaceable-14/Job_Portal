<?php
include '../components/connect.php';
session_start();
error_reporting(0);
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $com_id = filter_var($_POST['com_id'], FILTER_SANITIZE_STRING);
    $com_name = filter_var($_POST['com_name'], FILTER_SANITIZE_STRING);
    
    $questionsArray = [];
    $answersArray = [];

    for ($i = 1; $i <= $_POST['numQuestions']; $i++) {
        $questionsArray[] = filter_var($_POST['question' . $i], FILTER_SANITIZE_STRING);
        $answersArray[] = filter_var($_POST['answer' . $i], FILTER_SANITIZE_STRING);
    }

    // Convert to JSON for storing in DB
    $questionsJson = json_encode($questionsArray);
    $answersJson = json_encode($answersArray);

    // Check if company already exists
    $checkCompany = $conn->prepare("SELECT * FROM `mcq` WHERE com_id = ?");
    $checkCompany->execute([$com_id]);

    if ($checkCompany->rowCount() > 0) {
        $message[] = 'Company already exists!';
    } else {
        $insertMcq = $conn->prepare("INSERT INTO mcq (com_id, company_name, questions, answers) VALUES (?, ?, ?, ?)");
        $insertMcq->execute([$com_id, $com_name, $questionsJson, $answersJson]);
        $message[] = 'MCQ added successfully!';
    }
}

// Success popup message
if (isset($_SESSION['pop-up'])) {
    $message[] = 'Logged in successfully';
    unset($_SESSION['pop-up']);
}

// Fetch company details
$select_company = $conn->prepare("SELECT * FROM `companies` WHERE id = ?");
$select_company->execute([$admin_id]);
$fetch_company = $select_company->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Q&A Sections</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            margin-top: 10px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .question-section {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f1f1f1;
        }
        label {
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
        #submitButton {
            display: none; /* Initially hide the submit button */
        }
    </style>
    <script>
        function generateFields() {
            let container = document.getElementById("questionsContainer");
            container.innerHTML = ""; 
            let numQuestions = document.getElementById("numQuestions").value;
            
            for (let i = 1; i <= numQuestions; i++) {
                let section = document.createElement("div");
                section.className = "question-section";
                
                let questionLabel = document.createElement("label");
                questionLabel.textContent = "Question " + i + ":";
                let questionInput = document.createElement("input");
                questionInput.type = "text";
                questionInput.name = "question" + i;
                questionInput.required = true;
                
                let answerLabel = document.createElement("label");
                answerLabel.textContent = "Answer:";
                let answerInput = document.createElement("input");
                answerInput.type = "text";
                answerInput.name = "answer" + i;
                answerInput.required = true;
                
                section.appendChild(questionLabel);
                section.appendChild(questionInput);
                section.appendChild(answerLabel);
                section.appendChild(answerInput);
                
                container.appendChild(section);
            }

            // Show the submit button after fields are generated
            document.getElementById("submitButton").style.display = "block";
        }
    </script>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<?php include '../components/company_header.php' ?>

    <div class="container">
        <h1>Question and Answer Form</h1>

        <?php if (!empty($message)): ?>
            <p style="color: green;"><?= implode('<br>', $message); ?></p>
        <?php endif; ?>

        <form id="qaForm" method="post">
            <label for="numQuestions"><h3>Enter number of questions:</h3></label>
            <input type="number" id="numQuestions" name="numQuestions" min="1" max="10" required>
            <button type="button" onclick="generateFields()">Generate</button>

            <input type="hidden" name="com_id" value="<?= htmlspecialchars($fetch_company['id']); ?>">
            <input type="hidden" name="com_name" value="<?= htmlspecialchars($fetch_company['company_name']); ?>" readonly>

            <div id="questionsContainer"></div>

            <!-- Submit button initially hidden -->

            <button type="submit" name="submit" id="submitButton">Submit</button>
            
        </form>
       
        </div>
        <br><br><br>
 <h1 class="heading">Test Results</h1>
        <section class="orders">
            <div class="box-container">
        
<?php

if(isset($_POST['update'])){
    $payment_status = $_POST['verify_status'];
    $asd= $_POST['pid'];
    $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
    $update_payment = $conn->prepare("UPDATE `test_results` SET display = ? WHERE id = ? ");
    $update_payment->execute([$payment_status, $asd]);
    $message[] = 'application status updated!';
  }

    $select_company = $conn->prepare("SELECT * FROM `test_results` where company_name = ?");
    $select_company->execute([$fetch_company['company_name']]);
    if($select_company->rowCount() > 0){
        while($fetch_company = $select_company->fetch(PDO::FETCH_ASSOC)){
?>          
    <div class="flex-btn">
    <form action="" method="post">
        <div class="box-container">
            <div class="box">
                <?php 
                $asd= $fetch_company['user_id']; 
                
                //    echo $asd;
                $select_users = $conn->prepare("SELECT * FROM `users_details` where id = ?");
                $select_users->execute([$asd]);
                if($select_users->rowCount() > 0){
                    while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                        
                        ?>
                        <input type="hidden" name="pid" value="<?= $fetch_company['id']; ?>">
            <!-- <input type="text" readonly value="" id="">
            <input type="text" readonly value="<?=$fetch_company['score']?>" id=""> -->
            <p>Name : <span><?=$fetch_users['name']?></span></p>
            <p>Score : <span><?=$fetch_company['score']?></span></p>
            <select name="verify_status" class="select" default>
            <option selected disabled><?= $fetch_company['display']; ?></option>
            <option value="hide">hide</option>
            <option value="show">show</option>
        </select>
        <input type="submit" name="update" value="update" class="btn">
                        </div>
                </form>
            <?php
                }
            }  
                
            ?>
            <?php
                }
            }  
                
            ?>
                </div></div>
            </div>
        </section>
</div>

    <script src="../js/admin_script.js"></script>
    <footer>
      <center><p class="empty " style="margin-top: 15rem;  ">All rights reserved to Niteesh &copy; <?= date('M Y'); ?></p> </center>
  </footer>
</body>
</html>

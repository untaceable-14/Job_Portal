<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's latest test result
$select_results = $conn->prepare("SELECT * FROM `test_results` WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$select_results->execute([$user_id]);
$test_result = $select_results->fetch(PDO::FETCH_ASSOC);

if (!$test_result) {
    echo "<p>No test results found.</p>";
    exit;
}

// Fetch the corresponding MCQ data
$select_mcq = $conn->prepare("SELECT * FROM `mcq` WHERE id = ?");
$select_mcq->execute([$test_result['test_id']]);
$mcq_data = $select_mcq->fetch(PDO::FETCH_ASSOC);

// Decode the stored answers
$user_answers = json_decode($test_result['answers'], true);
$correct_answers = json_decode($mcq_data['answers'], true);
$questions = json_decode($mcq_data['questions'], true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
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
        .correct-answer {
            color: green;
            font-weight: bold;
        }
        .incorrect-answer {
            color: red;
            font-weight: bold;
        }
        .score {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-top: 15px;
        }
        button {
            margin-top: 15px;
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
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>
<?php include 'search_page.php'; ?>

<div class="container">
    <h2>Test Results</h2>
    <p><strong>Company:</strong> <?= htmlspecialchars($test_result['company_name']); ?></p>
    <p class="score">Your Score: <?= round($test_result['score'], 2); ?>%</p>

    <?php foreach ($questions as $index => $question): ?>
        <div class="question-section">
            <p><strong>Q<?= ($index + 1); ?>:</strong> <?= htmlspecialchars($question); ?></p>
            <p>Your Answer: 
                <span class="<?= (strtolower(trim($user_answers[$index] ?? '')) === strtolower(trim($correct_answers[$index]))) ? 'correct-answer' : 'incorrect-answer'; ?>">
                    <?= htmlspecialchars($user_answers[$index] ?? 'No Answer'); ?>
                </span>
            </p>
            <p>Correct Answer: <span class="correct-answer"><?= htmlspecialchars($correct_answers[$index]); ?></span></p>
        </div>
    <?php endforeach; ?>

    <a href="applications.php"><button>Go Back</button></a>
</div>

<?php include 'components/user_footer.php'; ?>

</body>
</html>

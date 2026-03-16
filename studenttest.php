<?php
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Fetch student ID from URL
$student_id = $_GET['student_id'];

// Fetch questions
$result = $conn->query("SELECT * FROM Questions");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize category scores
    $category_scores = [
        'Artistic' => 0,
        'Conventional' => 0,
        'Realistic' => 0,
        'Social' => 0,
        'Investigative' => 0,
        'Enterprising' => 0,
    ];

    foreach ($_POST['answers'] as $question_id => $answer) {
        $score = 0;
        switch ($answer) {
            case 'Strongly Agree': $score = 4; break;
            case 'Agree': $score = 3; break;
            case 'Disagree': $score = 2; break;
            case 'Strongly Disagree': $score = 1; break;
        }

        // Get the category of the question
        $question = $conn->query("SELECT category FROM Questions WHERE id = $question_id")->fetch_assoc();
        $category = $question['category'];

        // Add score to the appropriate category
        $category_scores[$category] += $score;

        // Save the answer in the Answers table
        $conn->query("INSERT INTO Answers (student_id, question_id, answer, score) VALUES ($student_id, $question_id, '$answer', $score)");
    }

    // Determine the top category
    $career_type = array_keys($category_scores, max($category_scores))[0];

    // Save the result in the Results table
    $conn->query("INSERT INTO Results (student_id, career_type) VALUES ($student_id, '$career_type')");

    // Redirect to result page
    header("Location: result.php?student_id=$student_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Test</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        
        <!--font awesome cdn link-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!-- custom css file link -->
        <link rel="stylesheet" href="major.css">
        
</head>
<body>
    
    <!--navigation-->
        <nav>
            <img src="logocg.png" alt="">
            <div class="navigation">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Get Started</a></li>
                    
                </ul>
            </div>
        </nav>
    
    <section id="test">
    
    <h2>Answer the Test</h2>
    
    <div class="container">
    <form method="POST" class="test">
        <?php 
        $question_number = 1; // Initialize question number
        while ($row = $result->fetch_assoc()) { ?>
            <label><?php echo $question_number . ". " . $row['question']; ?></label><br>
            <input type="radio" name="answers[<?php echo $row['id']; ?>]" value="Strongly Agree" required> Strongly Agree<br>
            <input type="radio" name="answers[<?php echo $row['id']; ?>]" value="Agree"> Agree<br>
            <input type="radio" name="answers[<?php echo $row['id']; ?>]" value="Disagree"> Disagree<br>
            <input type="radio" name="answers[<?php echo $row['id']; ?>]" value="Strongly Disagree"> Strongly Disagree<br><br>
            <?php $question_number++; // Increment question number ?>
        <?php } ?>
        <button class="hantar" type="submit">Submit Test</button>
    </form>
        </div>
    </section>
</body>
</html>

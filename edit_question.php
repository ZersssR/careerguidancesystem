<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start();
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Fetch the question data if a valid ID is provided
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    $result = $conn->query("SELECT * FROM Questions WHERE id = $question_id");
    $question_data = $result->fetch_assoc();

    if (!$question_data) {
        die("Question not found.");
    }
} else {
    die("No question ID provided.");
}


// Handle the form submission for updating the question
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $question = $_POST['question'];

    // Update the question in the database
    $conn->query("UPDATE Questions SET category = '$category', question = '$question' WHERE id = $question_id");
    $success = "Question updated successfully.";
    // Redirect back to the create_test page
    header("Location: create_test.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Question</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="latest.css">
</head>
<body>

    <nav>
        <input type="checkbox" name="toggler" id="toggler">
        <label for="toggler" class="fa fa-bars"></label>
        <img src="logocg.png" class="logo" alt="">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#" onclick="toggleFea()">Features</a></li>
            <li><a href="#">Get Started</a></li>
        </ul>
        <div class="sub-menu-wrap" id="subfea">
            <div class="sub-menu">
                <a href="teacher_dashboard.php" class="sub-menu-link"><i class="fa fa-home"></i><p>Home</p><span>></span></a>
                <a href="create_test.php" class="sub-menu-link"><i class="fa fa-users"></i><p>Test</p><span>></span></a>
                <a href="manage_students.php" class="sub-menu-link"><i class="fa fa-address-book"></i><p>Student</p><span>></span></a>
                <a href="studentsresult.php" class="sub-menu-link"><i class="fa fa-address-book"></i><p>Result</p><span>></span></a>
                <a href="studentchart.php" class="sub-menu-link"><i class="fa fa-signal"></i><p>Statistic</p><span>></span></a>
            </div>
        </div>
        <img class="user-pic" src="pokemon.jpg" alt="" onclick="toggleMenu()">
        <div class="sub-menu-wrap" id="subMenu">
            <div class="sub-menu">
                <div class="user-info">
                    <img src="pokemon.jpg">
                    <h3>Staff name</h3>
                </div>
                <hr>
                <a href="logout.php" class="sub-menu-link"><i class="fa fa-close"></i><p>Logout</p><span>></span></a>
            </div>
        </div>
    </nav>

    <div class="create">
        <h1>Edit Question</h1>

        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

<h2>Edit Question Details</h2>
        <form class="frg" method="POST" action="">
            <label for="category">Question Category:</label>
            <select name="category" required>
                <option value="Artistic" <?php echo $question_data['category'] == 'Artistic' ? 'selected' : ''; ?>>Artistic</option>
                <option value="Conventional" <?php echo $question_data['category'] == 'Conventional' ? 'selected' : ''; ?>>Conventional</option>
                <option value="Realistic" <?php echo $question_data['category'] == 'Realistic' ? 'selected' : ''; ?>>Realistic</option>
                <option value="Social" <?php echo $question_data['category'] == 'Social' ? 'selected' : ''; ?>>Social</option>
                <option value="Investigative" <?php echo $question_data['category'] == 'Investigative' ? 'selected' : ''; ?>>Investigative</option>
                <option value="Enterprising" <?php echo $question_data['category'] == 'Enterprising' ? 'selected' : ''; ?>>Enterprising</option>
            </select>
            <br>
            <label for="question">Question:</label>
            <input type="text" name="question" value="<?php echo $question_data['question']; ?>" required>
            <br>
            <button type="submit">Update Question</button>
        </form>
    </div>

    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }

        let subfea = document.getElementById("subfea");
        function toggleFea() {
            subfea.classList.toggle("open-subfea");
        }
    </script>

</body>
</html>
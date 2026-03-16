<?php
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student ID from URL
$student_id = $_GET['student_id'];




// Fetch student name
$student = $conn->query("SELECT name FROM Students WHERE id = $student_id")->fetch_assoc();

// Fetch career type for the student
$result = $conn->query("SELECT career_type FROM Results WHERE student_id = $student_id");
if ($result) {
    $row = $result->fetch_assoc();
    $career_type = $row['career_type'] ?? 'default_value'; // Default if not found
} else {
    die("Error fetching career type: " . $conn->error);
}

// Fetch jobs, descriptions, and explanations based on career type
$stmt = $conn->prepare("SELECT job, description, explanation FROM career_personality WHERE career_type = ?");
$stmt->bind_param("s", $career_type); // Assuming career_type is a string
$stmt->execute();
$jobs_result = $stmt->get_result();



// Fetch result for the student
$result = $conn->query("SELECT career_type FROM Results WHERE student_id = $student_id")->fetch_assoc();
$result = $conn->query("SELECT career_type FROM Results WHERE student_id = $student_id")->fetch_assoc();
$career_type = $result['career_type'];

// Suggested careers based on top category
$suggested_careers = [];
while ($job_row = $jobs_result->fetch_assoc()) {
    $suggested_careers[] = $job_row['job'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Result</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="major.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <img src="logocg.jpg" alt="Logo">
        <div class="navigation">
            <ul>
            <li><a href="index.html">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="role.html">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <!-- Result Section -->
    <section id="result">
        <div class="reminderss">
            <h1>Your Result</h1>
            <p><strong><?php echo $student['name']; ?></strong>, you are <strong><?php echo $career_type; ?></strong>.</p>
            <p><strong>Career Type:</strong> <?php echo $career_type; ?> 
            individuals are known for their creative, structured, or practical skills, depending on their category.</p>
            
            <h2>Suggested Suitable Careers</h2>
            <ul>
        <?php foreach ($suggested_careers as $career) { ?>
            <li><?php echo htmlspecialchars($career); ?>
            <a href="career_details.php?career=<?php echo urlencode($career); ?>">[Details]</a>
        </li>
        <?php } ?>
           </ul>

            <div class="btn">
                <a href="studentregistration.php" class="button">Home</a>
                <button class="button" onclick="window.print()">Save as PDF</button>
            </div>
        </div>
    </section>
</body>
</html>

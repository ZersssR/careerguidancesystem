<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user inputs
    $name = $_POST['name'];
    $ic = $_POST['ic'];
    $gender = $_POST['gender'];
    $class = $_POST['class'];
    $year = (int) $_POST['year']; // Explicitly cast year to integer

    // Use prepared statements to insert data safely
    $stmt = $conn->prepare("INSERT INTO Students (name, ic, gender, class, year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $ic, $gender, $class, $year);

    if ($stmt->execute()) {
        // Redirect to the test page with the student ID
        $student_id = $stmt->insert_id; // Get the last inserted ID
        header("Location: studenttest.php?student_id=$student_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Student Registration</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        
        <!-- Font Awesome CDN Link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!-- Custom CSS File Link -->
        <link rel="stylesheet" href="major.css">
    </head>
    <body>
        <!-- Navigation -->
        <nav>
            <img src="logocg.jpg" alt="">
            <div class="navigation">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="role.html">Get Started</a></li>
                </ul>
            </div>
        </nav>
        
        <!-- Registration -->
        <section id="registration">
            <div class="reminder">
                <h1>Please register</h1>
                <p>Kindly key in your details for data collection purpose</p>  
            </div>
            
            <div class="form">
                <h3>Details</h3>
                <form method="POST" class="frg">
                    <label>Name:</label>
                    <input type="text" name="name" required><br>
                    
                    <label>IC:</label>
                    <input type="text" name="ic" required><br>
                    
                    <label>Gender:</label>
                    <select name="gender" class="option">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select><br>
                    
                    <label>Class:</label>
                    <select name="class" class="option">
                        <option value="5Nafi'">5 Nafi'</option>
                        <option value="5IK">5 Ibnu Khathir</option>
                        <option value="5AA'">5 Abu Amru'</option>
                        <option value="5IA">5 Ibnu Amir</option>
                    </select><br>
                    
                    <label>Year:</label>
                    <input type="number" name="year" required><br>

                    <div class="btn">
                        <button type="submit" class="button">Register</button>
                    </div>
                </form>
            </div>
        </section>
    </body>
</html>

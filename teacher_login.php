<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user inputs
    $staff_id = $_POST['staff_id'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Teachers WHERE staff_id = ? AND password = ?");
    $stmt->bind_param("ss", $staff_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Start session and redirect to dashboard
        session_start();
        $_SESSION['teacher_id'] = $staff_id;
        header('Location: teacher_dashboard.php');
        exit();
    } else {
        $error = "Invalid staff ID or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Login</title>
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
                <li><a href="about.html">About</a></li>
                <li><a href="studentregistration.php">Get Started</a></li>
            </ul>
        </div>
    </nav>

    <!-- Login Section -->
    <section id="login">
        <div class="reminderrr">
            <h1>Teacher Login</h1>
            <p>Welcome back! Please log in to access your dashboard.</p>
        </div>

        <div class="form">
            <h3>Login Details</h3>
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
            <form method="POST" class="frg">
                <label for="staff_id">Staff ID:</label>
                <input type="text" name="staff_id" required><br>

                <label for="password">Password:</label>
                <input type="password" name="password" required><br>

                <div class="btn">
                    <button type="submit" class="button">Login</button>
                </div>
            </form>
        </div>
    </section>
</body>
</html>

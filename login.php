<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="major.css">
    
    <!-- SweetAlert for custom alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <title>Staff Login</title>
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
   
    
    <section id="login">
      <div class="wrapper">
        
        <div class="form">
            <div class="reminder">
                <h3>Login</h3>
                <p>Please enter your ID and password</p>
            </div>
            
            <form method="POST" class="frg"  action="admindashboard.html">
                <div class="input-box">
                    <label for="staff_id">Staff ID</label>
                    <span class="icon"><span class="fa fa-envelope"></span></span>
                    <input type="text" name="staff_id" id="staff_id" required>
                    
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <span class="icon"><span class="fa fa-lock"></span></span>
                    <input type="password" name="password" id="password" required>
                    
                </div>
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="#">Forgot Password?</a>
                </div>
                <br>
                
                <button type="submit" class="button">Login</button>
            </form>
        </div>
      </div>
    </section>
</body>
</html>

<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'careerguidancesystem');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];
    $password = $_POST['password'];

    // Prevent SQL injection
    $staff_id = $conn->real_escape_string($staff_id);
    $password = $conn->real_escape_string($password);

    // Query to check if the user exists
    $sql = "SELECT * FROM teachers WHERE id = '$staff_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user record
        $row = $result->fetch_assoc();
        
    // Verify password (assuming passwords are hashed using password_hash())
        if (password_verify($password, $row['password'])) {
            // Set session variables and redirect
            $_SESSION['staff_id'] = $row['id'];
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful',
                    text: 'Redirecting to dashboard...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'dashboard.php';
                });
            </script>";
        } else {
            // Invalid password
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please try again.'
                });
            </script>";
        }
    } else {
        // User ID not found
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'User Not Found',
                text: 'Staff ID does not exist. Please try again or register.'
            });
        </script>";
    }
}
$conn->close();
?>
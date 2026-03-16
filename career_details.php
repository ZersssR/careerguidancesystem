<?php
// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch career name from the URL
$career_name = $_GET['career'] ?? null; // Ensure career is provided
if (!$career_name) {
    die("Career name is required.");
}

// Prepare and execute the SQL statement to fetch career details
$stmt = $conn->prepare("SELECT job, job_description, qualifications FROM career_detail WHERE job = ?");
$stmt->bind_param("s", $career_name);
$stmt->execute();
$result = $stmt->get_result();

// Check if the career exists
if ($result->num_rows > 0) {
    $career_details = $result->fetch_assoc();
} else {
    die("No details found for this career.");
}

// Close statement and connection
$stmt->close();
$conn->close();

// Display results in HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($career_details['job']); ?> Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="details.css">

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
                        <a href="teacher_dashboard.php" class="sub-menu-link">
                            <i class="fa fa-home"></i>
                            <p>Home</p>
                            <span>></span>
                                
                        </a>
                        <a href="create_test.php" class="sub-menu-link">
                            <i class="fa fa-users" ></i>
                            <p>Test</p>
                            <span>></span>
                                
                        </a>
                        <a href="manage_students.php" class="sub-menu-link">
                            <i class="fa fa-address-book"></i>
                            <p>Student</p>
                            <span>></span>
                                
                        </a>
                        <a href="studentsresult.php" class="sub-menu-link">
                            <i class="fa fa-address-book"></i>
                            <p>Result</p>
                            <span>></span>
                                
                        </a>
                        <a href="studentchart.php" class="sub-menu-link">
                            <i class="fa fa-signal"></i>
                            <p>Statistic</p>
                            <span>></span>
                                
                        </a>
                        
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
                        
                        <!--<a href="#" class="sub-menu-link">
                            <i class="fa fa-users"></i>
                            <p>Edit profile</p>
                            <span>></span>
                                
                        </a>-->
                        
                        <a href="logout.php" class="sub-menu-link">
                            <i class="fa fa-close"></i>
                            <p>Logout</p>
                            <span>></span>
    
    
    <!--<form method="POST" action="logout.php">
            <button type="submit">Logout</button>
        </form>-->
                                
                        </a>
                        
                    </div>
                </div>
                </nav>

<div class="details">

<br>
<div class="isi">
    <h1>Details for <?php echo htmlspecialchars($career_details['job']); ?></h1>
    <h2>Description:</h2>
    <p><?php echo htmlspecialchars($career_details['job_description']); ?></p>
    
    <h2>Qualifications:</h2>
    <p><?php echo htmlspecialchars($career_details['qualifications']); ?></p>
</div>
</div>
   
</body>
</html>

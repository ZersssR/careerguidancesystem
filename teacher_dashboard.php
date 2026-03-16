
<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Fetch the logged-in teacher's details
$teacher_id = $_SESSION['teacher_id'];
$teacher = $conn->query("SELECT * FROM Teachers WHERE staff_id = '$teacher_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admincss.css">

</head>
<body>
    
    <div class="hero">
    
    <nav>
                
            <input type="checkbox" name="toggler" id="toggler">
            <label for="toggler" class="fa fa-bars"></label>
                
            <img src="logocg.png" class="logo" alt="">
            
                <ul>
                    <li><a href="index.html">Home</a></li>
                    
                    <li><a href="#" onclick="toggleFea()">Features</a></li>
                        
                    <li><a href="studentregistration.php">Get Started</a></li>
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
    
    <div class="content">
    
        <h3 class="i-name">Teacher Dashboard</h3>
            
            <div class="values">


<div class="val-box">
                    <i class="fa fa-users"></i>
                    <div>
                        <a href="create_test.php">Manage Test</a>
                    </div>
                </div>
                <div class="val-box">
                    <i class="fa fa-home"></i>
                    <div>
                        <a href="manage_students.php">Manage Student Data</a>
                    </div>
                </div>
                <div class="val-box">
                    <i class="fa fa-heart"></i>
                    <div>
                        <a href="teacher_chart_menu.PHP">View Student Statistic</a>
                    </div>
                </div>
            </div>
        
    
    </div>
    
    </div>
    
    <script>
            let subMenu = document.getElementById("subMenu");
            
            function toggleMenu(){
                subMenu.classList.toggle("open-menu");
            }
            
            let subfea = document.getElementById("subfea");
            
            function toggleFea(){
                subfea.classList.toggle("open-subfea");
            }
        </script>
    
</body>
</html>
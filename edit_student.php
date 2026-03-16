<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    // Updated query with LEFT JOIN to get career_type from Results table
    $query = "SELECT Students.*, Results.career_type 
              FROM Students 
              LEFT JOIN Results ON Students.id = Results.student_id 
              WHERE Students.id = $edit_id";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $student = $result->fetch_assoc();
    } else {
        die("Student not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $ic = $_POST['ic'];
    $gender = $_POST['gender'];
    $class = $_POST['class'];
    $year = $_POST['year'];
    $career_type = $_POST['career_type'];

    // Update student details
    $update_query = "UPDATE Students SET 
                     name = '{$conn->real_escape_string($name)}', 
                     ic = '{$conn->real_escape_string($ic)}', 
                     gender = '{$conn->real_escape_string($gender)}', 
                     class = '{$conn->real_escape_string($class)}', 
                     year = '{$conn->real_escape_string($year)}'
                     WHERE id = $id";

    $result = $conn->query($update_query);

    if ($result) {
        // Update career type in the Results table
        $career_query = "UPDATE Results SET career_type = '{$conn->real_escape_string($career_type)}' WHERE student_id = $id";
        $conn->query($career_query);

        echo "<script>alert('Student updated successfully');</script>";
        echo "<script>window.location.href='studentsresult.php';</script>";
    } else {
        echo "<script>alert('Error updating student: " . $conn->error . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Student</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="latest.css">
</head>
<body>
    
    <!--navigation-->
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
    
    <div class="w3-container">
        <div class="petok">
        <div class="form">
        <h2>Edit Student</h2>
        <form class="frg" method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            
            <!-- Name -->
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $student['name']; ?>" required><br>

<!-- IC -->
            <label>IC:</label>
            <input type="text" name="ic" value="<?php echo $student['ic']; ?>" required><br>
            
            <!-- Gender -->
            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php echo $student['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $student['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select><br>
            
            <!-- Class -->
            <label>Class:</label>
            <select name="class" required>
                <option value="5 Nafi" <?php echo $student['class'] == '5 Nafi' ? 'selected' : ''; ?>>5 Nafi'</option>
                <option value="5 Ibnu Kat" <?php echo $student['class'] == '5 Ibnu Kat' ? 'selected' : ''; ?>>5 Ibnu Khathir</option>
                <option value="5 Abu Amru" <?php echo $student['class'] == '5 Abu Amru' ? 'selected' : ''; ?>>5 Abu Amru'</option>
                <option value="5 Ibnu Ami" <?php echo $student['class'] == '5 Ibnu Ami' ? 'selected' : ''; ?>>5 Ibnu Amir</option>
            </select><br>
            <!-- Year -->
            <label>Year:</label>
            <input type="text" name="year" value="<?php echo $student['year']; ?>" required><br>
            
            <!-- Career Type Dropdown -->
            <label>Career Type:</label>
            <select name="career_type" required>

                
            <option value="Artistic" <?php echo $student['career_type'] == 'Artistic' ? 'selected' : ''; ?>>Artistic</option>
                <option value="Social" <?php echo $student['career_type'] == 'Social' ? 'selected' : ''; ?>>Social</option>
                <option value="Realistic" <?php echo $student['career_type'] == 'Realistic' ? 'selected' : ''; ?>>Realistic</option>
                <option value="Investigative" <?php echo $student['career_type'] == 'Investigative' ? 'selected' : ''; ?>>Investigative</option>
                <option value="Enterprising" <?php echo $student['career_type'] == 'Enterprising' ? 'selected' : ''; ?>>Enterprising</option>
                <option value="Conventional" <?php echo $student['career_type'] == 'Conventional' ? 'selected' : ''; ?>>Conventional</option>
            </select><br>
            
            <!-- Submit and Cancel Buttons -->
            <button type="submit" class="w3-button w3-blue">Update</button>
            <a href="studentsresult.php" class="w3-button w3-red">Cancel</a>
        </form>
    </div>
    </div>
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
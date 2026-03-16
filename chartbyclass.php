<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'CareerGuidanceSystem';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch filters from the form
$careerTypeFilter = isset($_GET['career_type']) ? $_GET['career_type'] : '';

// SQL Query with Filters
$query = "SELECT Students.class AS class, Results.career_type AS career_type, COUNT(*) AS count 
          FROM Results
          JOIN Students ON Results.student_id = Students.id
          WHERE (:careerTypeFilter = '' OR Results.career_type = :careerTypeFilter)
          GROUP BY Students.class, Results.career_type";

$stmt = $conn->prepare($query);
$stmt->execute([
    ':careerTypeFilter' => $careerTypeFilter,
]);

// Process query results into dataPoints
$dataPoints = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dataPoints[] = [
        "y" => (int)$row['count'],
        "label" => $row['class'],
        "indexLabel" => $row['career_type'], // Use career_type directly for the indexLabel
    ];
}

// Fetch unique career types for the dropdown
$careerTypes = $conn->query("SELECT DISTINCT career_type FROM Results")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE HTML>
<html>
<head>
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="latest.css">
    
<script>
window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    theme: "light2",
    title:{
        text: "<?php echo $careerTypeFilter ? 'Total Students in ' . htmlspecialchars($careerTypeFilter) : 'Total Students For All Career Personality'; ?>"
    },
    axisY: {
        title: "Number of Students"
    },
    data: [{
        type: "column",
        yValueFormatString: "#,##0",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
});
chart.render();

}
</script>


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
    
    <div class="petok">
<!-- Filter Form -->
<form class="bycl" method="GET" action="">
    <label for="career_type">Filter by Career Type:</label>
    <select name="career_type" id="career_type">
        <option value="">All</option>
        <?php foreach ($careerTypes as $type): ?>
            <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $careerTypeFilter === $type ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($type); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button class="button" type="submit">Filter</button>
</form>

<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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
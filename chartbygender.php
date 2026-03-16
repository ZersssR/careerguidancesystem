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

// SQL Query to calculate totals by gender (First Graph)
$queryGender = "
    SELECT 
        Students.gender AS gender,
        COUNT(*) AS total 
    FROM Results
    JOIN Students ON Results.student_id = Students.id
    WHERE (:careerTypeFilter = '' OR Results.career_type = :careerTypeFilter)
    GROUP BY Students.gender";

$stmtGender = $conn->prepare($queryGender);
$stmtGender->execute([
    ':careerTypeFilter' => $careerTypeFilter
]);

$genderDataPoints = [];
while ($row = $stmtGender->fetch(PDO::FETCH_ASSOC)) {
    $genderDataPoints[] = [
        "y" => (int)$row['total'],
        "name" => $row['gender'] === 'Male' ? 'Male' : 'Female',
        "color" => $row['gender'] === 'Male' ? "#546BC1" : "#DA1884"
    ];
}

// SQL Query to calculate totals by class and gender (Second Graph)
$queryClassGender = "
    SELECT 
        Students.class AS class, 
        Students.gender AS gender,
        COUNT(*) AS total
    FROM Results
    JOIN Students ON Results.student_id = Students.id
    WHERE (:careerTypeFilter = '' OR Results.career_type = :careerTypeFilter)
    GROUP BY Students.class, Students.gender";

$stmtClassGender = $conn->prepare($queryClassGender);
$stmtClassGender->execute([
    ':careerTypeFilter' => $careerTypeFilter
]);

$classGenderDataPoints = [];
while ($row = $stmtClassGender->fetch(PDO::FETCH_ASSOC)) {
    $classGenderDataPoints[] = [
        "y" => (int)$row['total'],
        "label" => $row['class'],
        "color" => $row['gender'] === 'Male' ? "#546BC1" : "#DA1884",
        "gender" => $row['gender'] === 'Male' ? 'Male' : 'Female'
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
   
window.onload = function () {
    // First Graph: Male vs Female (Doughnut Chart)
    var genderChart = new CanvasJS.Chart("genderChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Male vs Female Students"
        },
        data: [{
            type: "doughnut",
            innerRadius: "75%",
            showInLegend: true,
            legendText: "{name}",
            indexLabel: "{name}: {y}",
            dataPoints: <?php echo json_encode($genderDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    genderChart.render();

    // Second Graph: Male/Female by Class and Gender (Column Chart)
    var classGenderChart = new CanvasJS.Chart("classGenderChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
        },
        axisY: {
            title: "Number of Students"
        },
        data: [{
            type: "column",
            legendText: "Class",
            indexLabel: "{y}",
            dataPoints: <?php echo json_encode($classGenderDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    classGenderChart.render();

// Handle clicks on the doughnut chart (Male/Female)
    genderChart.options.data[0].click = function (e) {
        // Filter the column chart based on the clicked gender (Male/Female)
        var filteredDataPoints = <?php echo json_encode($classGenderDataPoints, JSON_NUMERIC_CHECK); ?>.filter(function (item) {
            return item.gender === e.dataPoint.name;
        });

        // Hide the doughnut chart and show the column chart
        document.getElementById("genderChartContainer").style.display = "none";
        document.getElementById("classGenderChartContainer").style.display = "block";


// Update the column chart with filtered data
        classGenderChart.options.data[0].dataPoints = filteredDataPoints;
        classGenderChart.options.title.text = "Total  (" + e.dataPoint.name + ") student ";
        classGenderChart.render();
    };

    // Sorting functionality for the column chart
    document.getElementById("sortButton").onclick = function() {
        // Sort the data by class (alphabetically)
        classGenderChart.options.data[0].dataPoints.sort(function(a, b) {
            // Sorting by class (alphabetically)
            return a.label.localeCompare(b.label);
        });
        classGenderChart.render();
    };

    // Show doughnut chart and hide column chart initially
    document.getElementById("genderChartContainer").style.display = "block";
    document.getElementById("classGenderChartContainer").style.display = "none";
};
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
<form method="GET" action="">
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

<!-- First Graph: Male vs Female (Doughnut Chart) -->
<div id="genderChartContainer" style="height: 370px; width: 100%; margin-top: 20px;"></div>

<!-- Second Graph: Students by Class and Gender (Column Chart) -->
<div id="classGenderChartContainer" style="height: 370px; width: 100%; margin-top: 20px; display: none;"></div>

    </div>

<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

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
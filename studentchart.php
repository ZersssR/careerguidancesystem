
<?php
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
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
$yearFilter = isset($_GET['year']) ? $_GET['year'] : '';
$genderFilter = isset($_GET['gender']) ? $_GET['gender'] : '';
$careerTypeFilter = isset($_GET['career_type']) ? $_GET['career_type'] : '';

// SQL Query with Filters
$query = "SELECT career_type AS career_type, COUNT(*) AS count 
          FROM Results
          JOIN Students ON Results.student_id = Students.id
          WHERE (:classFilter = '' OR Students.class = :classFilter)
          AND (:yearFilter = '' OR Students.year = :yearFilter)
          AND (:genderFilter = '' OR Students.gender = :genderFilter)
          AND (:careerTypeFilter = '' OR career_type = :careerTypeFilter)
          GROUP BY career_type";

$stmt = $conn->prepare($query);
$stmt->execute([
    ':classFilter' => $classFilter,
    ':yearFilter' => $yearFilter,
    ':genderFilter' => $genderFilter,
    ':careerTypeFilter' => $careerTypeFilter,
]);
$pieData = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pieData[] = ['label' => $row['career_type'], 'y' => $row['count']];
}

// Fetching data for Column Chart (same as Pie Chart)
$columnData = $pieData;
?>

<!DOCTYPE HTML>
<html>
<head>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="admincss.css">
    
<script>
window.onload = function () {

    // Pie Chart
    var pieChart = new CanvasJS.Chart("pieChartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        title: {
            text: "Percentage Distribution of Students Across Career Categories"
        },
        data: [{
            type: "pie",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 16,
            indexLabel: "{label} - #percent%",
            yValueFormatString: "#,##0",
            dataPoints: <?php echo json_encode($pieData, JSON_NUMERIC_CHECK); ?>
        }]
    });
    pieChart.render();

    // Column Chart
    var columnChart = new CanvasJS.Chart("columnChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Percentage Distribution of Students Across Career Categories"
        },
        axisY: {
            title: "Percentage,%",
        },
        axisX: {
            title: "Career Type",
        },
        data: [{
            type: "column",
            yValueFormatString: "#,##0",
            indexLabel: "{y}",
            indexLabelPlacement: "inside",
            indexLabelFontColor: "white",
            dataPoints: <?php echo json_encode($columnData, JSON_NUMERIC_CHECK); ?>
        }]
    });
    columnChart.render();

};
</script>
</head>
<body>
    <section id="chart">
<!--navigation-->
        <nav>
                
            <input type="checkbox" name="toggler" id="toggler">
            <label for="toggler" class="fa fa-bars"></label>
                
            <img src="logocg.png" class="logo" alt="">
            
            <ul>
                    <li><a href="teacher_dashboard.php">Home</a></li>
                    
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

<div class="graph">
<!-- Filters Form -->
<h2>Please select which filter you want to apply</h2>

<form class="search" method="GET" action="">
    <label for="class">Class:</label>
    <select name="class" id="class">
        <option value="">All Classes</option>
        <option value="5 Nafi">5 Nafi'</option>
        <option value="5 Ibnu Kat">5 Ibnu Khathir</option>
        <option value="5 Abu Amru">5 Abu Amru'</option>
        <option value="5 Ibnu Ami">5 Ibnu Amir</option>
    </select>
    
    <label for="year">Year:</label>
    <select name="year" id="year">
        <option value="">All Years</option>
        <option value="2023">2023</option>
        <option value="2024">2024</option>
        <option value="2025">2025</option>
    </select>
    
    <label for="gender">Gender:</label>
    <select name="gender" id="gender">
        <option value="">All Genders</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>

    <label for="career_type">Career Type:</label>
    <select name="career_type" id="career_type">
        <option value="">All Career Types</option>
        <option value="Artistic">Artistic</option>
        <option value="Conventional">Conventional</option>
        <option value="Realistic">Realistic</option>
        <option value="Social">Social</option>
        <option value="Investigative">Investigative</option>
        <option value="Enterprising">Enterprising</option>
    </select>

    <button class="button" type="submit" class="btn">Apply Filters</button>
</form>
    
<!-- Pie Chart Container -->
<div id="pieChartContainer" style="height: 370px; width: 100%;"></div>

<!-- Column Chart Container -->
<div id="columnChartContainer" style="height: 370px; width: 100%; margin-top: 50px;"></div>

</div>
    </section>
    
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
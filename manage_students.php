<?php
$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Delete from the Results table first
    $delete_results_query = "DELETE FROM Results WHERE student_id = $delete_id";
    if ($conn->query($delete_results_query) === TRUE) {
        // After deleting from Results, now delete from the Students table
        $delete_students_query = "DELETE FROM Students WHERE id = $delete_id";
        if ($conn->query($delete_students_query) === TRUE) {
            echo "<script>alert('Record deleted successfully');</script>";
            echo "<script>window.location.href='studentsresult.php';</script>";
        } else {
            echo "<script>alert('Error deleting student record: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error deleting related results: " . $conn->error . "');</script>";
    }
}

// Initialize filters
$class_filter = isset($_GET['class']) ? $_GET['class'] : '';
$name_filter = isset($_GET['name']) ? $_GET['name'] : '';
$year_filter = isset($_GET['year']) ? $_GET['year'] : '';

// Base query
$query = "SELECT Students.id, Students.name, Students.ic, Students.gender, Students.class, Students.year, Results.career_type 
          FROM Students 
          LEFT JOIN Results ON Students.id = Results.student_id 
          WHERE 1=1";

// Add filters to the query
if (!empty($class_filter)) {
    $query .= " AND Students.class = '" . $conn->real_escape_string($class_filter) . "'";
}
if (!empty($name_filter)) {
    $query .= " AND Students.name LIKE '%" . $conn->real_escape_string($name_filter) . "%'";
}
if (!empty($year_filter)) {
    $query .= " AND Students.year = '" . $conn->real_escape_string($year_filter) . "'";
}

// Execute query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher View Results</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admincss.css">
</head>
<body>
    <!-- Navigation -->
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
                <a href="logout.php" class="sub-menu-link">
                    <i class="fa fa-close"></i>
                    <p>Logout</p>
                    <span>></span>
                </a>
            </div>
        </div>
    </nav>

    
    <div class="searchstudent">
        <!-- Search and Filters -->
        <section id="filters" class="w3-container w3-padding">
            <h2 class="search">Search Students</h2>
            <form class="search_ques" method="GET" action="" class="searching">
                <label for="class">Class:</label>
                <select name="class" class="option">
                    <option value="">All Classes</option>
                    <option value="5 Nafi" <?php echo $class_filter == "5 Nafi" ? 'selected' : ''; ?>>5 Nafi'</option>
                    <option value="5 Ibnu Kat" <?php echo $class_filter == "5 Ibnu Kat" ? 'selected' : ''; ?>>5 Ibnu Khathir</option>
                    <option value="5 Abu Amru" <?php echo $class_filter == "5 Abu Amru" ? 'selected' : ''; ?>>5 Abu Amru'</option>
                    <option value="5 Ibnu Ami" <?php echo $class_filter == "5 Ibnu Ami" ? 'selected' : ''; ?>>5 Ibnu Amir</option>
                </select><br>
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($name_filter); ?>" placeholder="Enter student name"><br>
                <label for="year">Year:</label>
                <input type="text" name="year" value="<?php echo htmlspecialchars($year_filter); ?>" placeholder="Enter year"><br>
                <button class="button" type="submit" class="w3-button w3-blue">Search</button>
            </form>
        </section>

        <!-- Results Section -->
        <section id="results" class="w3-container w3-padding">
        <h2>Manage Students</h2>
            <div class="board">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>IC</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Year</th>
                            <th>Career Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0) { ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['ic']; ?></td>
                                    <td><?php echo $row['gender']; ?></td>
                                    <td><?php echo $row['class']; ?></td>
                                    <td><?php echo $row['year']; ?></td>
                                    <td><?php echo $row['career_type'] ? $row['career_type'] : 'N/A'; ?></td>
                                    <td>
                                        <a class="edit" href="edit_student.php?edit_id=<?php echo $row['id']; ?>">Edit</a>
                                        <a class="delete" href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="8">No results found for the given criteria.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
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

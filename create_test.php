<?php
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header('Location: teacher_login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'CareerGuidanceSystem');

// Handle adding, deleting, and editing questions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_question'])) {
        // Add a new question
        $category = $_POST['category'];
        $question = $_POST['question'];

        $conn->query("INSERT INTO Questions (category, question) VALUES ('$category', '$question')");
        $success = "Question added successfully.";
    } elseif (isset($_POST['delete_question'])) {
        // Delete a question
        $question_id = $_POST['question_id'];
        $conn->query("DELETE FROM Questions WHERE id = $question_id");
        $success = "Question deleted successfully.";
    } elseif (isset($_POST['edit_question'])) {
        // Edit a question
        $question_id = $_POST['question_id'];
        $category = $_POST['category'];
        $question = $_POST['question'];

        $conn->query("UPDATE Questions SET category = '$category', question = '$question' WHERE id = $question_id");
        $success = "Question updated successfully.";
    }
}

// Fetch all questions from the database with optional search filters
$search_category = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

$sql = "SELECT * FROM Questions WHERE 1=1";
if ($search_category) {
    $sql .= " AND category = '$search_category'";
}
if ($search_query) {
    $sql .= " AND question LIKE '%$search_query%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Test</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admincss.css">
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
                <a href="teacher_dashboard.php" class="sub-menu-link"><i class="fa fa-home"></i><p>Home</p><span>></span></a>
                <a href="create_test.php" class="sub-menu-link"><i class="fa fa-users"></i><p>Test</p><span>></span></a>
                <a href="manage_students.php" class="sub-menu-link"><i class="fa fa-address-book"></i><p>Student</p><span>></span></a>
                <a href="studentsresult.php" class="sub-menu-link"><i class="fa fa-address-book"></i><p>Result</p><span>></span></a>
                <a href="studentchart.php" class="sub-menu-link"><i class="fa fa-signal"></i><p>Statistic</p><span>></span></a>
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
                <a href="logout.php" class="sub-menu-link"><i class="fa fa-close"></i><p>Logout</p><span>></span></a>
            </div>
        </div>
    </nav>

    <div class="create">
        <h1>Create Test and Manage Test</h1>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>

        <h2>Add New Question</h2>
        <form method="POST" action="">
            <label for="category">Question Category:</label>
            <select name="category" required>
                <option value="Artistic">Artistic</option>
                <option value="Conventional">Conventional</option>
                <option value="Realistic">Realistic</option>
                <option value="Social">Social</option>
                <option value="Investigative">Investigative</option>
                <option value="Enterprising">Enterprising</option>
            </select>
            <label for="question">Question:</label>
            <input type="text" name="question" required>
            <button type="submit" name="add_question">Add Question</button>
        </form>

        <h2>Search and Manage Questions</h2>
        <form method="GET" action="">
            <label for="category">Category:</label>
            <select name="category">
                <option value="">All</option>
                <option value="Artistic" <?php echo $search_category == 'Artistic' ? 'selected' : ''; ?>>Artistic</option>
                <option value="Conventional" <?php echo $search_category == 'Conventional' ? 'selected' : ''; ?>>Conventional</option>
                <option value="Realistic" <?php echo $search_category == 'Realistic' ? 'selected' : ''; ?>>Realistic</option>
                <option value="Social" <?php echo $search_category == 'Social' ? 'selected' : ''; ?>>Social</option>
                <option value="Investigative" <?php echo $search_category == 'Investigative' ? 'selected' : ''; ?>>Investigative</option>
                <option value="Enterprising" <?php echo $search_category == 'Enterprising' ? 'selected' : ''; ?>>Enterprising</option>
            </select>
            <label for="search_query">Search Question:</label>
            <input type="text" name="search_query" value="<?php echo $search_query; ?>" placeholder="Search...">
            <button type="submit">Search</button>
        </form>

        <h2>All Questions</h2>
        <div class="board">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Question</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['question']; ?></td>
                            <td>
                                <form method="POST" action="" style="display:inline;">
                                    <input type="hidden" name="question_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_question" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                                </form>
                                <a href="edit_question.php?question_id=<?php echo $row['id']; ?>" class="edit">Edit</a> <!-- Add Edit link here -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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

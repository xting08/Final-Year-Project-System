<?php 
require_once '../MIDDLEWARE/db_connect.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Admin");
$conn = OpenCon();

function searchProject($conn, $search) {
    $sql = "SELECT 
            project.id as project_id,
            project.title as project_title,
            project.student_id,
            project.supervisor_id,
            project.progression,
            student.user_id,
            student.full_name
        FROM project 
        JOIN student ON project.student_id = student.user_id
        WHERE project.title LIKE '%$search%' OR project.student_id LIKE '%$search%'";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function displayProjects($projects) {
    foreach ($projects as $project) {
        $progressionBar = $project['progression'] / 6 * 100;
        echo "<div class='progress-card'>";
        echo "<div class='student'>";
        echo "<h3>{$project['full_name']}</h3>";
        echo "<h3>{$project['student_id']}</h3>";
        echo "</div>";
        echo "<div class='progress-info'>";
        echo "<h3>{$project['project_title']}</h3>";
        echo "<div class='progress-bar-container'>";
        echo "<div class='progress-bar' style='width: {$progressionBar}%'></div>";
        echo "</div>";
        echo "<p>Progression: {$progressionBar}%</p>";
        echo "</div>";
        echo "<div class='view-btn'>";
        echo '<button id="view-project" onclick="window.location.href=\'view-project.php?student_id=' . $project['student_id'] . '\'">VIEW</button>';
        echo "</div>";
        echo "</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/progression.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Project Management</title>
</head>

<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>
    <div class="search-container">
        <form id="search-form" action="">
            <input type="text" id="search" name="search" placeholder="Search for a project" required>
            <button type="submit" id="search-btn">
                SEARCH <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>

    <div class="container">
        <?php 
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $projects = searchProject($conn, $search);
            displayProjects($projects);
        } 
        else {
            $sql = "SELECT project.*, student.* 
                FROM project 
                JOIN student ON project.student_id = student.user_id
                WHERE project.admin_approval_status = 'Approved'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()){
                $progressionBar = $row['progression'] / 6 * 100;
                echo "<div class='progress-card'>";
                echo "<div class='student'>";
                echo "<h3>{$row['full_name']}</h3>";
                echo "<h3>{$row['student_id']}</h3>";
                echo "</div>";
                echo "<div class='progress-info'>";
                echo "<h3>{$row['title']}</h3>";
                echo "<div class='progress-bar-container'>";
                echo "<div class='progress-bar' style='width: {$progressionBar}%'></div>";
                echo "</div>";
                echo "<p>Progression: {$progressionBar}%</p>";
                echo "</div>";
                echo "<div class='view-btn'>";
                echo '<button id="view-project" onclick="window.location.href=\'view-project.php?student_id=' . $row['student_id'] . '\'">VIEW</button>';
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</body>

</html>
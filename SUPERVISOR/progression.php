<?php
require_once '../MIDDLEWARE/db_connect.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Supervisor");


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function searchProject($search){
    $conn = OpenCon();
    $sql = "SELECT 
            project.id as project_id,
            project.title as project_title,
            project.student_id,
            project.supervisor_id,
            project.is_complete,
            project.progression,
            student.user_id,
            student.full_name
        FROM project 
        JOIN student ON project.student_id = student.user_id
        WHERE project.supervisor_id = " . $_SESSION['user_id'] . " 
        AND is_complete = 1 
        AND (project.title LIKE '%$search%' OR project.student_id LIKE '%$search%')";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
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
    <?php 
    include '../HEADER/supervisor-header.inc.php';
    require_once '../SUPERVISOR/project-supervisor.php';
    ?>
    
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
            $projects = searchProject($search);
            ProjectSupervisor::displayProjects($projects);
        } else {
            ProjectSupervisor::displayAllProgressCard($_SESSION['user_id']);
        }
        ?>
    </div>
    
</body>

</html>
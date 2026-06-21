<?php
    require_once '../MIDDLEWARE/db_connect.php';
    session_start();

    $connect = OpenCon();

    $sql = "SELECT * FROM supervisor WHERE user_id = '".$_SESSION['user_id']."'";
    $result = mysqli_query($connect,$sql);
    $row = mysqli_fetch_array($result);

    if (isset($_POST['submit'])) {
        $projectTitle = $_POST['title'];
        $projectType = $_POST['project-types'];
        $isTaken = '0';
        $supervisorId = $_SESSION['user_id'];

        $sql = "INSERT INTO title_proposed (title, project_type, is_taken, supervisor_id) VALUES ('$projectTitle', '$projectType', '$isTaken', '$supervisorId')";
        $insert = $connect -> query($sql);

        if ($insert) {
            echo "<script>alert('Project Added Successfully!');</script>";
            echo "<script>window.location.href='../SUPERVISOR/project-management.php';</script>";

        } else {
            echo "<script>alert('Failed to add project!');</script>";
            echo "<script>window.location.href='../SUPERVISOR/project-management.php';</script>";
        }
    }
?>
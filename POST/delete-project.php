<?php
    require_once '../MIDDLEWARE/db_connect.php';

    if (isset($_GET['id'])) {
        $connect = OpenCon();

        $sql = "DELETE FROM title_proposed WHERE id = '" . $_GET['id'] . "'";
        $result = mysqli_query($connect,$sql);

        if ($result) {
            echo "<script>alert('Project deleted successfully!');</script>";
            echo "<script>window.location.href='../SUPERVISOR/project-management.php';</script>";
        } else {
            echo 'Error: ' . $sql . '<br>' . mysqli_error($connect);
        }
    }
?>
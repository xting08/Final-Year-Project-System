<?php
    require_once '../MIDDLEWARE/db_connect.php';
    session_start();
    $connect = OpenCon();

    $roleQuery = "SELECT role FROM users WHERE user_id = " . $_SESSION['user_id'];
    $role = $connect -> query($roleQuery);
    $roleRow = $role -> fetch_assoc();


    if ($_POST['approve']) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM project WHERE id = $id";
        $result = mysqli_query($connect, $sql);
        $projectRow = $result -> fetch_assoc();

        $approve = 'Approved';
        if ($roleRow['role'] == 'Supervisor') {
            $sql = "UPDATE project SET supervisor_approval_status = '$approve' WHERE id = $id";
            mysqli_query($connect, $sql);
            $studentSql = "UPDATE student SET supervisor_id = " . $_SESSION['user_id'] . " WHERE user_id = " . $projectRow['student_id'];
            mysqli_query($connect, $studentSql);
            echo "<script>history.back()</script>";

        } else if ($roleRow['role'] == 'Admin') {
            $sql = "UPDATE project SET admin_approval_status = '$approve' WHERE id = $id";
            mysqli_query($connect, $sql);
            echo "<script>history.back()</script>";
        } else {
            echo "No action taken";
        }

    } else if ($_POST['reject']) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM project WHERE id = $id";
        $result = mysqli_query($connect, $sql);
        $projectRow = $result -> fetch_assoc();

        $updateQuery = "UPDATE title_proposed SET is_taken = 0, student_id = NULL WHERE student_id = " . $projectRow['student_id'];
        mysqli_query($connect, $updateQuery);

        if ($roleRow['role'] == 'Supervisor') {
            $reject = 'Rejected';
            $sql = "UPDATE project SET supervisor_approval_status = '$reject' WHERE id = $id";
            mysqli_query($connect, $sql);
            echo "<script>history.back()</script>";
        } else if ($roleRow['role'] == 'Admin') {
            $reject = 'Rejected';
            $sql = "UPDATE project SET admin_approval_status = '$reject' WHERE id = $id";
            mysqli_query($connect, $sql);
            echo "<script>history.back()</script>";
        } else {
            echo "No action taken";
        }
    } else {
        echo "No action taken";
    }
?>
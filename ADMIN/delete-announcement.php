<?php
    require_once("../MIDDLEWARE/db_connect.php");
    $conn = OpenCon();

    if (isset($_GET['id'])) {
        $announcement_id = $_GET['id'];
        $sql = "DELETE FROM announcement WHERE id = $announcement_id";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: announcement.php");
            exit();  
        } 
        else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } 
    else {
        echo "No ID provided for deletion.";
    }
?>

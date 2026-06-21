<?php
require_once("../MIDDLEWARE/db_connect.php");
require_once("../MIDDLEWARE/role-state-management.php");
roleStateManagement("Student");

if (isset($_GET['attachment']) && isset($_GET['student_id'])) {
    $connect = OpenCon();
    
    $chapterId = (int)$_GET['attachment'];
    $studentId = $_GET['student_id'];
    
    $sql = "SELECT submission_attachment_" . $chapterId . " as file_content 
            FROM project 
            WHERE student_id = '$studentId'";
            
    $result = $connect->query($sql);

    if ($row = $result->fetch_assoc()) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="Chapter' . $chapterId . '.pdf"');
        echo $row['file_content'];
    }
    
    $connect->close();
} else {
    echo "Invalid request.";
}
?>
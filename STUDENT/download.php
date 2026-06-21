<?php
require_once("../MIDDLEWARE/db_connect.php");
require_once("../MIDDLEWARE/role-state-management.php");
roleStateManagement("Student");

if (isset($_GET['meeting_id'])) {
    $meeting_id = $_GET['meeting_id'];
    
    $conn = OpenCon();
    $sql = "SELECT file_content, file_name FROM meeting_slot WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$meeting_id]);
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && !empty($row['file_content'])) {
        $file_name = $row['file_name'];
        $file_content = $row['file_content'];
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . strlen($file_content));
        
        echo $file_content;
        exit();
    }
}

echo "File not found";
?> 
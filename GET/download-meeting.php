<?php
require_once "../MIDDLEWARE/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['meeting_id'])) {
    $meeting_id = $_GET['meeting_id'];
    
    $conn = OpenCon();
    $sql = "SELECT attachment FROM meeting_slot WHERE id = $meeting_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    if ($row && !empty($row['attachment'])) {
        $file_content = $row['attachment'];
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="meeting_document.pdf"');
        header('Content-Length: ' . strlen($file_content));
        
        echo $file_content;
        exit();
    }
}

echo "File not found";
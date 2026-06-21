<?php
require_once "../MIDDLEWARE/db_connect.php";

if (isset($_GET['message_id'])) {
    $messageId = $_GET['message_id'];
    
    try {
        $conn = OpenCon();
        $sql = "SELECT attachment FROM message WHERE id = $messageId";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row && !empty($row['attachment'])) {
            $file_content = $row['attachment'];
            $file_name = isset($row['file_name']) ? $row['file_name'] : 'meeting_document.pdf'; 
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
            header('Content-Length: ' . strlen($file_content));
            
            echo $file_content;
            exit();
        }
    } catch (Exception $e) {
        error_log("Error downloading attachment: " . $e->getMessage());
        exit("Error downloading file");
    }
}

exit("File not found");

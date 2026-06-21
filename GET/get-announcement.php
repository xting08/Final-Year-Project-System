<?php
require_once("../MIDDLEWARE/db_connect.php");
$conn = OpenCon();

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM announcement WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'title' => $row['title'],
            'description' => $row['description'],
            'attachment' => $row['attachment'] ?? '',
            'author' => $row['author'] ?? '',
            'posted_date' => $row['posted_date'] ?? ''
        ]);
    } else {
        echo json_encode(['error' => 'Announcement not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}

CloseCon($conn);
?>
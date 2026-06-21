<?php
require_once '../MIDDLEWARE/db_connect.php';
session_start();
$conn = OpenCon();
$query = "SELECT full_name FROM admin WHERE user_id = " .$_SESSION['user_id'];
$result = mysqli_query($conn, $query);
$row = $result -> fetch_assoc();
    
    
if(isset($_POST['submit'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $row['full_name'];
    $date = date('Y-m-d');
    $attachment = "";

    if(!empty($_FILES['attachment']['name'])) {
        $target_dir = "../UPLOADS/";
        $attachment_name = time().'_'.basename($_FILES['attachment']['name']);
        $target_file = $target_dir . $attachment_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'doc', 'docx'];

        if(in_array($fileType, $allowedTypes)) {
            if(move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                
            }
            else {
                echo "Failed to upload attachment";
                exit;
            }
        }
        else {
            echo "Invalid file type";
            exit;
        }
    }

    if(!empty($id)) {
        $sql = "UPDATE announcement SET title='$title', description = '$description', author = '$author', posted_date = '$date'";
        if(!empty($attachment_name)) {
            $sql .= ", attachment = '$attachment_name'";
        }

        $sql .= " WHERE id = '$id'";
    }

    else{
        $sql = "INSERT INTO announcement (title, attachment, description, author, posted_date) VALUES ('$title', '$attachment_name', '$description', '$author', '$date')";
    }
   
    $result = $conn -> query($sql);
    if($result){
        header("location: ../ADMIN/announcement.php");
    }
    else{
        echo "Failed to post announcement";
    }
}

else{
    header("location: ../ADMIN/announcement.php");
}
?>
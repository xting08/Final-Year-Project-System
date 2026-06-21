<?php
require_once '../MIDDLEWARE/db_connect.php';
session_start();
$conn = OpenCon();

if(isset($_POST['submit'])){
    $fullName = $_POST['full-name'];
    $role = $_POST['role'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $spec = $_POST['specialisation'];
    $title = $_POST['title']; 

    if($role === 'Student'){
        $studentSql = "UPDATE student SET full_name = '$fullName', role = '$role', id = '$role', email = '$email', specialisation = '$spec' WHERE user_id = '$id'";
        $studentResult = $conn->query($studentSql);
        if($studentResult){
            header("Location: ../ADMIN/manage-profile.php?id=".$id."&status=success");
            exit(); 
        }
        else{
            echo "Failed to update";
        }
    }
        
    else if($role === 'Admin'){
        $adminSql = "UPDATE admin SET full_name = '$fullName', role = '$role', id = '$role', email = '$email' WHERE user_id = '$id'";
        $adminResult = $conn->query($adminSql);
        if($adminResult){
            header("Location: ../ADMIN/manage-profile.php?id=".$id."&status=success");
            exit(); 
        }
        else{
            echo "Failed to update";
        }
    }

    else if($role === 'Supervisor'){
        $supSql = "UPDATE supervisor SET full_name = '$fullName', role = '$role', id = '$role', email = '$email' WHERE user_id = '$id'";
        $supResult = $conn->query($supSql);
        if($supResult){
            header("Location: ../ADMIN/manage-profile.php?id=".$id."&status=success");
            exit(); 
        }
        else{
            echo "Failed to update";
        }
    }

    else{
        echo "Failed to update";
    }
}

else if(isset($_POST['remove'])){
    $fullName = $_POST['full-name'];
    $role = $_POST['role'];
    $id = $_POST['id'];
    $email = $_POST['email'];
    $spec = $_POST['specialisation'];
    $title = $_POST['title']; 

    if($role === 'Student'){
        $studentSql = "DELETE FROM student WHERE user_id = '$id'";
        $studentResult = $conn->query($studentSql);
        if($studentResult){
            header("Location: ../ADMIN/user-profile-management.php");
            exit(); 
        }
        else{
            echo "Failed to delete";
        }
    }
        
    else if($role === 'Admin'){
        $adminSql = "DELETE FROM admin WHERE user_id = '$id'";
        $adminResult = $conn->query($adminSql);
        if($adminResult){
            header("Location: ../ADMIN/user-profile-management.php");
            exit(); 
        }
        else{
            echo "Failed to delete";
        }
    }

    else if($role === 'Supervisor'){
        $supSql = "DELETE FROM supervisor WHERE user_id = '$id'";
        $supResult = $conn->query($supSql);
        if($supResult){
            header("Location: ../ADMIN/user-profile-management.php");
            exit(); 
        }
        else{
            echo "Failed to delete";
        }
    }

    else{
        echo "Failed to delete";    
    }
}

else{
    header("location: ../ADMIN/manage-profile.php");
}
?>
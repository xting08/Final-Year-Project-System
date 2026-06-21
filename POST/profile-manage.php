<?php
require_once '../MIDDLEWARE/db_connect.php';
session_start();
$conn = OpenCon();

if(isset($_POST['submit'])){
    if(empty($_POST['new-password']) || empty($_POST['confirm-password'])){
        echo "<script>alert('Please fill in the blank(s)');</script>";
    }

    else{
        $id = $_POST['id'];
        $newPass = $_POST['new-password'];
        $confirmPass = $_POST['confirm-password'];
        $role = $_POST['role'];

        if($newPass === $confirmPass){
            if($role === 'Admin'){
                $sql = "UPDATE admin SET password = '$newPass' WHERE user_id = '$id'";
                $result = $conn -> query($sql);

                if($result){
                    header("Location: ../ADMIN/user.php?status=success");
                    exit();
                }
                else{
                    echo "Failed to update password";
                }
            }

            else if($role === 'Student'){
                $sql = "UPDATE student SET password = '$newPass' WHERE user_id = '$id'";
                $result = $conn -> query($sql);

                if($result){
                    header("Location: ../STUDENT/user.php?status=success");
                    exit();
                }
                else{
                    echo "Failed to update password";
                }
            }

            else if($role === 'Supervisor'){
                echo "Failed to update password";
            }
        }

        else{
            echo "Password does not match";
        }
    }
}

else{
    header("location: ../".$role."/user.php");
}
?>
<?php
    require_once '../MIDDLEWARE/db_connect.php';
    $connect = OpenCon();

    if (isset($_POST['submit'])) {
        if ($_POST['password'] != $_POST['c-password']) {
            echo "<script>alert('Password and Confirm Password do not match!');</script>";
            echo "<script>window.location.href='register.php';</script>";
        } else {
            $userId = $_POST['user-id'];
            $userPasswd = $_POST['password'];
            $userName = $_POST['full-name'];
            $userContact = $_POST['phone'];
            $userEmail = $_POST['email'];
            $userRole = $_POST['role'];

            $sql = "INSERT INTO $userRole (user_id, password, full_name, contact_no, email, role) VALUES ('$userId', '$userPasswd', '$userName', '$userContact', '$userEmail', '$userRole')";
            $insert = $connect -> query($sql);

            if ($insert) {
                header("location:../ADMIN/user-profile-management.php");
            } else {
                echo "<script>alert('Registration Failed!');</script>";
            }
        }
    } else {
        header("location:register-user.php");
    }
?>
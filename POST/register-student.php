<?php
    require_once '../MIDDLEWARE/db_connect.php';

    $connect = OpenCon();

    if (isset($_POST['submit'])) {
        if($_POST['password'] != $_POST['c-password']) {
            echo "<script>alert('Password and Confirm Password do not match!');</script>";
            echo "<script>window.location.href='register.php';</script>";
        } else {
            $studentId = $_POST['student-id'];
            $studentPasswd = $_POST['password'];
            $studentName = $_POST['full-name'];
            $studentContact = $_POST['phone'];
            $studentEmail = $_POST['email'];
            $studentRole = "Student";
            $studentCollab = $_POST['is-collab'];
            $supervisorId = null;
            $studentSpecialisation = $_POST['specialisation'];

            $sql = "INSERT INTO student (user_id, password, full_name, contact_no, email, role, is_collab, specialisation) VALUES ('$studentId', '$studentPasswd', '$studentName', '$studentContact', '$studentEmail', '$studentRole', '$studentCollab', '$studentSpecialisation')";
            $insert = $connect -> query($sql);

            if ($insert) {
                header("location:../login.php");
            } else {
                echo "<script>alert('Registration Failed!');</script>";
            }
        }
    } else {
        header("location:register.php");
    }
?>
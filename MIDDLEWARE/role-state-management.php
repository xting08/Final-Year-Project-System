<?php
    require_once "db_connect.php";
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    function roleStateManagement($pageRole){
        $currentRole = $_SESSION['role'];
        if($currentRole != $pageRole){            
            echo "<script>alert('You are not authorized to access this page');</script>";
            echo "<script>alert('Your role: " . $currentRole . ", Required role: " . $pageRole . "');</script>";
            echo "<script>window.location.href = '../login.php';</script>";
            exit();
        }   
    }
?>

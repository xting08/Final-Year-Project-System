<?php require_once 'db_connect.php';

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    function authentication($user_id, $password){
        $conn = OpenCon();

        $query = "SELECT role FROM users WHERE user_id = '$user_id' AND password = '$password'";
        $result = $conn -> query($query);

        if ($result -> num_rows == 1){
            $row = $result -> fetch_assoc();
            $role = $row['role'];

            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            
            if ($role == "Student"){
                $query = "SELECT supervisor_approval_status, admin_approval_status FROM project WHERE student_id = '$user_id'";
                $result = $conn -> query($query);

                if ($result -> num_rows == 1){
                    $row = $result -> fetch_assoc();
                    $supervisor_approval_status = $row['supervisor_approval_status'];
                    $admin_approval_status = $row['admin_approval_status'];

                    if ($supervisor_approval_status == 'Approved' && $admin_approval_status == 'Approved'){
                        $_SESSION['project_approval_status'] = 'Approved';
                        header("Location: ../STUDENT/main.php");
                    } else {
                        $_SESSION['project_approval_status'] = 'Pending';
                        header("Location: ../STUDENT/partial_main.php");
                    }
                } else {
                    $_SESSION['project_approval_status'] = 'Pending';
                    header("Location: ../STUDENT/partial_main.php");
                }
            } else if ($role == "Supervisor"){
                header("Location: ../SUPERVISOR/main.php");
            } else if ($role == "Admin"){
                header("Location: ../ADMIN/main.php");
            }
        } else {
            echo "<script>alert('Invalid User ID or Password!');</script>";
            echo "<script>window.location.href='../login.php';</script>";
        }
    };
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];
    
        authentication($user_id, $password);
    }
?>
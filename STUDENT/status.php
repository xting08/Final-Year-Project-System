<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/status.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Check Status</title>
</head>

<body>
    <?php 
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once '../MIDDLEWARE/db_connect.php';
        $connect = OpenCon();
    
        $sql = "SELECT * FROM project WHERE student_id = '" . $_SESSION['user_id'] . "'";
        $result = $connect -> query($sql);
        
        if ($result->num_rows == 0) {
            echo "<script>alert('No project proposal found!');</script>";
            echo "<script>window.location.href='partial_main.php';</script>";
            exit();
        }
        
        $rows = $result->fetch_assoc();
        
        if ($rows['supervisor_approval_status'] == 'Pending' || 
            $rows['supervisor_approval_status'] == 'Rejected' ||
            $rows['admin_approval_status'] == 'Pending' ||
            $rows['admin_approval_status'] == 'Rejected') {
            include '../HEADER/student-partial-header.inc.php';
        } else {
            include '../HEADER/student-header.inc.php';
        }
        
        $supervisorSql = "SELECT full_name FROM supervisor WHERE user_id = " . $rows['supervisor_id'];
        $result = $connect -> query($supervisorSql);    
        $supervisor = $result -> fetch_assoc();

        $studentSql = "SELECT full_name FROM student WHERE user_id = " . $rows['student_id'];
        $result = $connect -> query($studentSql);
        $student = $result -> fetch_assoc();    
    ?>
    <button id="back-btn" onclick="
    <?php 
        if ($rows['supervisor_approval_status'] == 'Pending' || 
            $rows['supervisor_approval_status'] == 'Rejected' ||
            $rows['admin_approval_status'] == 'Pending' ||
            $rows['admin_approval_status'] == 'Rejected') {
            echo "window.location.href='partial_main.php';";
        } else {
            echo "window.location.href='main.php';";
        }
    ?>">
    <i class="fa-solid fa-delete-left"></i>&nbsp&nbsp BACK</button>
    <div class="status-container">
        <h2>Status: </h2>
        <div class="status-bar">
            <div class="status status-approve">Submitted</div>
            <div class="status <?php echo ($rows['supervisor_approval_status'] == 'Approved') ? 'status-approve' : (($rows['supervisor_approval_status'] == "Rejected") ? 'status-reject' : 'status-pending'); ?>">Supervisor</div>
            <div class="status <?php echo ($rows['admin_approval_status'] == 'Approved') ? 'status-approve' : (($rows['admin_approval_status'] == "Rejected") ? 'status-reject' : 'status-pending'); ?>">Admin</div>
        </div>
    </div>
    <section class="project-details">

        <div class="project-container">
            <h2>Project Information</h2>
            <table>
                <tr>
                    <td>Project Title</td>
                    <td>:</td>  
                    <td><?php echo $rows['title']; ?></td>
                </tr>
                <tr>
                    <td>Project Type</td>
                    <td>:</td>  
                    <td><?php echo $rows['project_type']; ?></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>:</td>  
                    <td><?php echo $rows['description']; ?></td>
                </tr>
                <tr>
                    <td>Motivation</td>
                    <td>:</td>  
                    <td><?php echo $rows['motivation']; ?></td>
                </tr>
                <tr>
                    <td>Objective</td>
                    <td>:</td>  
                    <td><?php echo $rows['objectives']; ?></td>
                </tr>
                <tr>
                    <td>Student</td>
                    <td>:</td>  
                    <td><?php echo $student['full_name'] . " - " . $rows['student_id']; ?></td>
                <tr>
                    <td>Supervisor</td>
                    <td>:</td>  
                    <td><?php echo $supervisor['full_name'] . " - " . $rows['supervisor_id']; ?></td>
                </tr>
            </table>
        </div>
    </section>
</body>


</html>

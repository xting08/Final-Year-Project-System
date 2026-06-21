<?php
    require_once("../MIDDLEWARE/db_connect.php");
    require_once("../MIDDLEWARE/role-state-management.php");
    $conn = OpenCon();
    roleStateManagement("Student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/announcement.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Announcement Page</title>
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
        $rows = $result->fetch_assoc();
        
        if ($result->num_rows == 0) {
            include '../HEADER/student-partial-header.inc.php';
        }
        else if ($rows['supervisor_approval_status'] == 'Pending' || 
            $rows['supervisor_approval_status'] == 'Rejected' ||
            $rows['admin_approval_status'] == 'Pending' ||
            $rows['admin_approval_status'] == 'Rejected') {
            include '../HEADER/student-partial-header.inc.php';
        } else {
            include '../HEADER/student-header.inc.php';
        }
    ?>
    
    <div class="announcement-container">
        <ul class="announcement-list">
        <?php
                $sql = "SELECT * FROM announcement";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li class='announcement-item'>";
                            echo "<div class='announcement-header'>";
                            echo "<div class='admin-info'>";
                            echo "<span>".$row['author']."</span> | <span>".$row['posted_date']."</span>";
                            echo "</div>";

                        echo "</div>";
                        echo "<div class='announcement-content'>";
                        echo "<h2>".$row['title']."</h2>";
                        if ($row['attachment']) {
                            $filePath = "../UPLOADS/".$row['attachment'];
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                            if(in_array($fileExtension, $imageExtensions)) {
                                echo "<img src='".$filePath."' alt='Attachment' class='announcement-img'>";
                            }
                            else {
                                echo "<p><a href='".$filePath."' target='_blank'>Download Attachment</a></p>";
                            }
                        }
                        echo "<p>".$row['description']."</p>";
                        echo "</div>";
                        echo "</li>";
                        }
                    } else {
                        echo "<li class='announcement-item'><p>No posted announcement.</p></li>";
                    }
                }
            ?>
        </ul>
    </div>
</body>
</html>
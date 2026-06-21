<?php
    require_once '../MIDDLEWARE/db_connect.php';
    function isCollabCheck() {
        if (!isset($_SESSION) || !isset($_SESSION['user_id'])) {
            header("Location: ../login.php");
            exit();
        }

        $connect = OpenCon();
        $query = 'SELECT is_collab FROM student WHERE user_id = ' . $_SESSION['user_id'];
        $result = $connect -> query($query);
        if($result -> num_rows > 0) {
            $row = $result -> fetch_assoc();
            $isCollab = $row['is_collab'];
            if ($isCollab == 0) {
                $projectQuery = "SELECT * FROM project WHERE student_id = " . $_SESSION['user_id'];
                $projectResult = $connect->query($projectQuery);
                if ($projectResult->num_rows > 0) {;
                    return;
                }
                echo '<li><a href="submit-individual.php">SUBMIT PROPOSAL (INDIVIDUAL)</a></li>';
            } else if ($isCollab == '1') {
                $projectQuery = "SELECT * FROM project WHERE student_id = " . $_SESSION['user_id'];
                $projectResult = $connect->query($projectQuery);
                if ($projectResult->num_rows > 0) {;
                    return;
                }
                echo '<li><a href="submit-group.php">SUBMIT PROPOSAL (COLLABORATION)</a></li>';
            }
        }
        $connect->close();
    }
?>
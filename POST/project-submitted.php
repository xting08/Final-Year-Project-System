<?php
    require_once '../MIDDLEWARE/db_connect.php';
    session_start();

    $connect = OpenCon();

    $studentQuery = 'SELECT * FROM student WHERE user_id = "' . $_SESSION['user_id'] . '"';
    $studentResult = $connect -> query($studentQuery);
    $studentRow = $studentResult -> fetch_assoc();
    
    if (isset($_POST['submit'])) {
        $supervisorQuery = 'SELECT user_id FROM supervisor WHERE full_name = "' . $_POST['supervisor'] . '" OR user_id = "' . $_POST['supervisor'] . '"';
        $supervisorResult = $connect -> query($supervisorQuery);
        $supervisorRow = $supervisorResult -> fetch_assoc();

        // Individual / Collaboration: Student 1 Information
        $projectTitle = $_POST['title'];
        $projectType = $_POST['project-type'];
        $projectDes = $_POST['description'];
        $projectMot = $_POST['motivation'];
        $projectObj = $_POST['objective'];
        $isCollab = $studentRow['is_collab'];
        $supervisor_approval_status = 'Pending';
        $studentId = $studentRow['user_id'];
        if ($supervisorRow) {
            $supervisorId = $supervisorRow['user_id'];
        } else {
            echo "<script>alert('Supervisor not found!');</script>";
            echo "<script>window.location.href='../STUDENT/submit-individual.php';</script>";
        }
        $projectProgress = '0';
        $projectAttach = $_POST['proposal-1'];
        $propose = $_POST['propose'];

        if ($propose == "Supervisor-Propose") {
            // Get project type from title_proposed table
            $getTypeQuery = "SELECT project_type FROM title_proposed WHERE title = '$projectTitle' AND supervisor_id = '$supervisorId'";
            $typeResult = $connect->query($getTypeQuery);
            $typeRow = $typeResult->fetch_assoc();
            $projectType = $typeRow['project_type'];  // Update project type with the one from proposed title
            
            $sql = "UPDATE title_proposed SET is_taken = '1', student_id = '$studentId' WHERE title = '$projectTitle' AND supervisor_id = '$supervisorId'";
            $insert = $connect->query($sql);
        }

        if ($studentRow['is_collab'] == 0) {
            $sql = "INSERT INTO project (title, project_type, description, motivation, objectives, is_collab, supervisor_approval_status, student_id, supervisor_id, progression, submission_attachment, propose) VALUES ('$projectTitle', '$projectType', '$projectDes', '$projectMot',  '$projectObj', '$isCollab', '$supervisor_approval_status', '$studentId', '$supervisorId', '$projectProgress', '$projectAttach', '$propose')";
            $insert = $connect -> query($sql);
            if ($insert) {
                echo "<script>alert('Submission Sucessfully!');</script>";
                echo "<script>window.location.href='../STUDENT/partial_main.php';</script>";
            } else {
                echo "<script>alert('Submission Failed!');</script>";
                echo "<script>window.location.href='../STUDENT/submit-individual.php';</script>";
            }
        } else if ($studentRow['is_collab'] == 1) {
            $coSupervisorQuery = 'SELECT user_id FROM supervisor WHERE full_name = "' . $_POST['co-supervisor'] . '"';
            $coSupervisorResult = $connect -> query($coSupervisorQuery);
            $coSupervisorRow = $coSupervisorResult -> fetch_assoc();

            $student2Query = 'SELECT * FROM student WHERE full_name = "' . $_POST['collab-student'] . '"';
            $student2Result = $connect -> query($student2Query);
            $student2Row = $student2Result -> fetch_assoc();

            // Collaboration: Student 2 Information
            $projectTitle2 = $_POST['title'];
            $projectType2 = $_POST['project-type2'];
            $projectDes2 = $_POST['description'];
            $projectMot2 = $_POST['motivation'];
            $projectObj2 = $_POST['objective'];
            $isCollab2 = '1';
            $supervisor_approval_status2 = 'Pending';
            $studentId2 = $student2Row['user_id'];
            $coSupervisorId =  $coSupervisorRow['user_id'];
            $projectProgress2 = '0';
            $projectAttach2 = $_POST['proposal-2'];

            if ($student2Row && ($student2Row['user_id'] == $_POST['collab-student-id'])) {
                if ($studentId != $studentId2) {
                    $sql1 = "INSERT INTO project (title, project_type, description, motivation, objectives, is_collab, supervisor_approval_status, student_id, supervisor_id, progression, submission_attachment, propose) VALUES ('" . $projectTitle . "', '" . $projectType . "', '" . $projectDes . "', '" . $projectMot . "',  '" . $projectObj . "', '" . $isCollab . "', '" . $supervisor_approval_status . "', '" . $studentId . "', '" . $supervisorId . "', '" . $projectProgress . "', '" . $projectAttach . "', '" . $propose . "')";
                    $sql2 = "INSERT INTO project (title, project_type, description, motivation, objectives, is_collab, supervisor_approval_status, student_id, supervisor_id, progression, submission_attachment, propose) VALUES ('" . $projectTitle2 . "', '" . $projectType2 . "', '" . $projectDes2 . "', '" . $projectMot2 . "',  '" . $projectObj2 . "', '" . $isCollab2 . "', '" . $supervisor_approval_status2 . "', '" . $studentId2 . "', '" . $coSupervisorId . "', '" . $projectProgress2 . "', '" . $projectAttach2 . "', '" . $propose . "')";
                    $insert1 = $connect -> query($sql1);
                    $insert2 = $connect -> query($sql2);
                    if ($insert1 && $insert2) {
                        echo "<script>alert('Submission Sucessfully!');</script>";
                        echo "<script>window.location.href='../STUDENT/partial_main.php';</script>";
                    } else {
                        echo "<script>alert('Submission Failed!');</script>";
                        echo "<script>window.location.href='../STUDENT/submit-group.php';</script>";
                    }
                } else {
                    echo "<script>alert('Cannot choose yourself for Student 2!')</script>";
                    echo "<script>window.location.href='../STUDENT/submit-group.php';</script>";
                }
            } else {
                echo "<script>alert('Student 2 Information are NOT MATCHED!');</script>";
                echo "<script>window.location.href='../STUDENT/submit-group.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Submission Failed!');</script>";
        echo "<script>window.location.href='../STUDENT/submit-individual.php';</script>";
    }

?>
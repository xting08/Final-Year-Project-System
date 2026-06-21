<?php
    require_once '../MIDDLEWARE/db_connect.php';
    require_once "../MIDDLEWARE/role-state-management.php";
    roleStateManagement("Supervisor");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $conn = OpenCon();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['project_id'])) {
            $project_id = $_GET['project_id'];
            
            $sql = "SELECT project.*, student.full_name, student.user_id 
                    FROM project 
                    JOIN student ON project.student_id = student.user_id
                    WHERE project.id = $project_id AND project.supervisor_id = {$_SESSION['user_id']}";
                    
            $result = $conn->query($sql);
            
            if (!$result) {
                die("Query failed: " . $conn->error);
            }
            
            $project = $result->fetch_assoc();
            
            if (!$project) {
                header("Location: marksheet.php");
                exit();
            }

            $isDisableBtn = false;
            $sql = "SELECT * FROM marksheet WHERE supervisor_id = {$_SESSION['user_id']} AND student_id = {$project['student_id']}";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                $isDisableBtn = true;
            }
        } else {
            header("Location: marksheet.php");
            exit();
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $project_id = $_POST['project_id'];
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $general_efforts = $_POST['general_efforts'];
        $written_report = $_POST['written_report'];
        $oral_presentation = $_POST['oral_presentation'];
        $prototype = $_POST['prototype'];
        
        if (submitAssessment($conn, $student_id, $student_name, $general_efforts, $written_report, $oral_presentation, $prototype)) {
            header("Location: marksheet.php");
            exit();
        } else {
            echo "<script>alert('Assessment submission failed');</script>";
        }
    }

    function submitAssessment($conn, $student_id, $student_name, $general_efforts, $written_report, $oral_presentation, $prototype) {
        $sql = "INSERT INTO marksheet (supervisor_id, student_id, student_name, general_efforts, written_report, oral_presentation, prototype) 
                VALUES ('{$_SESSION['user_id']}', '$student_id', '$student_name', $general_efforts, $written_report, $oral_presentation, $prototype)";
        

        if ($conn->query($sql)) {
            return true;
        } else {
            return false;
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/marksheet.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Project Assessment</title>
</head>

<body>
    <?php include '../HEADER/supervisor-header.inc.php'; ?>

    <main>
        <div id="pdf">
            <div class="pdf">
                <object class="pdf" data="../PDF/Assignment_Specifications.pdf" width="800" height="650">
                </object>
            </div>
        </div>

        <div id="assessment">
            <div class="student-info">
                <h2><span><?php echo $project['full_name'] ?></span> | <span><?php echo $project['student_id'] ?></span></h2>
            </div>
            <hr/>
            <div class="mark">
                <form action="assessment.php" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $project['user_id']; ?>">
                    <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($project['full_name']); ?>">
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    <table class="marksheet">
                        <thead>
                            <tr>
                                <th></th>
                                <th>5 - Excellent</th>
                                <th>4 - Good</th>
                                <th>3 - Average</th>
                                <th>2 - Basic</th>
                                <th>1 - Below</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>General Efforts</td>
                                <td><input type="radio" name="general_efforts" value="5" required></td>
                                <td><input type="radio" name="general_efforts" value="4"></td>
                                <td><input type="radio" name="general_efforts" value="3"></td>
                                <td><input type="radio" name="general_efforts" value="2"></td>
                                <td><input type="radio" name="general_efforts" value="1"></td>
                            </tr>
                            <tr>
                                <td>Written Report</td>
                                <td><input type="radio" name="written_report" value="5" required></td>
                                <td><input type="radio" name="written_report" value="4"></td>
                                <td><input type="radio" name="written_report" value="3"></td>
                                <td><input type="radio" name="written_report" value="2"></td>
                                <td><input type="radio" name="written_report" value="1"></td>
                            </tr>
                            <tr>
                                <td>Oral Presentation</td>
                                <td><input type="radio" name="oral_presentation" value="5" required></td>
                                <td><input type="radio" name="oral_presentation" value="4"></td>
                                <td><input type="radio" name="oral_presentation" value="3"></td>
                                <td><input type="radio" name="oral_presentation" value="2"></td>
                                <td><input type="radio" name="oral_presentation" value="1"></td>
                            </tr>
                            <tr>
                                <td>Prototype</td>
                                <td><input type="radio" name="prototype" value="5" required></td>
                                <td><input type="radio" name="prototype" value="4"></td>
                                <td><input type="radio" name="prototype" value="3"></td>
                                <td><input type="radio" name="prototype" value="2"></td>
                                <td><input type="radio" name="prototype" value="1"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="submit-button">
                        <button type="submit" class="btn-submit" <?php echo $isDisableBtn ? 'disabled' : ''; ?>>Submit Assessment</button>
                        <?php echo $isDisableBtn ? '<p class="disabled-text">You have already submitted the assessment</p>' : ''; ?>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>
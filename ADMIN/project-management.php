<?php
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Admin");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/project-management.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Project Proposal Management</title>
</head>

<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>
    <?php include '../GET/func-project-management.php'; ?>
    
    <main>
    <section id="project-approval">
            <div id="project-search">
                <h1>Project Approval</h1>
                <form id="search-form" action="" method="post">
                    <input type="text" id="search-title" name="search-title" placeholder="Search for a project" required>
                    <button type="submit" name="search" id="search-btn">
                        SEARCH <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>

            <?php 
                if (count($_POST) > 0) {
                    $connect = OpenCon();
                    $sql = "SELECT id, student_id, title, propose, description FROM project WHERE title = '" . $_POST['search-title'] . "' AND supervisor_approval_status = 'Pending'";
                    $result = mysqli_query($connect, $sql);
                    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

                    if (count($row) > 0) {
                        foreach ($row as $value) {
                            $studentSql = "SELECT full_name FROM student WHERE user_id = '" . $value['student_id'] . "'";
                            $studentNameResult = mysqli_query($connect, $studentSql);
                            $studentNameRow = mysqli_fetch_array( $studentNameResult, MYSQLI_ASSOC);
                            $studentName = $studentNameRow['full_name'];
                            
                            echo '<div class="project-card">
                                    <div class="student">
                                        <h3 style="text-align: center; font-size: 1em;">' . $studentName . '</h3>
                                        <h3>' . $value['student_id'] . '</h3>
                                        <h5>' . $value['propose'] . '</h5>
                                    </div>
                                    <div class="project-details">
                                        <h3>' . $value['title'] . '</h3>
                                        <p>' . $value['description'] . '</p>
                                    </div>
                                    <div class="view-btn">
                                        <button id="view-project" onclick="window.location.href=\'status.php?id=' . $value['id'] . '\'">VIEW</button>
                                    </div>
                                    </div>';
                        }
                    } else {
                        echo '<div class="project-card">
                                <h3>NO PROJECT AVAILABLE</h3>
                            </div>';
                    }
                } else {
                    displayProjectApprovalAdmin();
                }
            ?>
        </section>
    </main>
</body>

</html>
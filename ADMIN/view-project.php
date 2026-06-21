<?php
require_once '../ADMIN/project-admin.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Admin");

$project = new ProjectAdmin($_GET['student_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/project.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Admin - Project View</title>
</head>

<body>
    <aside id="progress">
        <!-- Display the progress bar -->
        <?php $project->printProgressionBar() ?>
        <h3>Progression</h3>
        <hr />
        <h3><span><?php echo $project->studentName ?></span> | <span><?php echo $project->studentId ?></span></h3>
        <h3><span>Project Title: <?php echo $project->title ?></span></h3>
        <p><span>Project Description: <?php echo $project->description ?></span></p>
        <p><span>Motivation: <?php echo $project->motivation ?></span></p>
        <p><span>Project Objective and Scope: <?php echo $project->objectives ?></span></p>
    </aside>

    <?php include '../HEADER/admin-header.inc.php'; ?>

    <main>
        <section id="project">
            <section id="submission">
                <div class="title-button">
                    <h2>Project Submission</h2>
                </div>
                <hr id="line">
                <?php $project->printChapters() ?>
            </section>
        </section>  
    </main>
</body>

</html>

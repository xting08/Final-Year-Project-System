<?php
require_once '../SUPERVISOR/project-supervisor.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Supervisor");

$project = new ProjectSupervisor($_GET['student_id']);

if (isset($_GET['action']) && $_GET['action'] === 'mark-complete' && isset($_GET['student_id'])) {
    $project->markProjectCompletion($_GET['student_id'], $project->progression);
}

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
    <title>Project</title>
</head>

<body>
    <aside id="progress">
        <?php $project->printProgressionBar() ?>
        <h3>Progression</h3>
        <hr />
        <h3><span><?php echo $project->studentName ?></span> | <span><?php echo $project->studentId ?></span></h3>
        <h3><span>Project Title: <?php echo $project->title ?></span></h3>
        <p><span>Project Description: <?php echo $project->description ?></span></p>
        <p><span>Motivation: <?php echo $project->motivation ?></span></p>
        <p><span>Project Objective and Scope: <?php echo $project->objectives ?></span></p>

    </aside>

    <?php include '../HEADER/supervisor-header.inc.php'; ?>

    <main>
        <section id="project">
            <?php require_once("project-planning-supervisor.php"); ?>

            <section id="submission">
                <div class="title-button">
                    <h2>Project Submission</h2>
                </div>
                <hr / id="line">
                <?php $project->printChapters() ?>
            </section>
            <button id="mark-complete" onclick="markProjectCompletion('<?php echo $project->studentId ?>')">Mark as Complete</button>
        </section>  
    </main>

    <script>
        function markProjectCompletion(studentId) {
            if (confirm('Are you sure you want to mark this project as complete?')) {
                window.location.href = `view-project.php?action=mark-complete&student_id=${studentId}`;
            }
        }
    </script>
</body>

</html>
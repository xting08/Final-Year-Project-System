<?php 
include("project-object.php");
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Student");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $project = new Project($_SESSION['user_id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES)) {
        foreach ($_FILES as $key => $file) {
            if (preg_match('/chapter(\d+)/', $key, $matches)) {
                $chapterId = $matches[1];
                $project->editAttachment($chapterId);
                break;
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload']) && isset($_FILES['chapter' . $_POST['upload']])) {
        $project->uploadFile($_POST['upload']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])){
        $project->createTodo();
    }
    
    if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['week'])) {
        $project->deleteTodoWeek($_GET['week']);
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
    <script src="updateItemTodo.js"></script>
    <title>Project</title>
</head>

<body>
    
    <aside id="progress">
        <?php $project -> printProgressionBar() ?>
        <h3>Progression</h3>
        <hr/>
        <h3><span><?php echo $project->studentName; ?></span> | <span><?php echo $project->studentId; ?></span></h3>
        <h3><span>Project Title: <?php echo $project->title; ?></span></h3>
        <p><span><strong>Project Description: </strong> <?php echo $project->description; ?></span></p>
        <p><span><strong>Motivation: </strong> <?php echo $project->motivation; ?></span></p>
        <p><span><strong>Project Objective: </strong> <?php echo $project->objectives; ?></span></p>
    </aside>

    <?php include '../HEADER/student-header.inc.php'; ?>

    <main>
        <section id="project">
            <?php require_once("project-planning.php"); ?>  
            
            <section id="submission">
                <div class="title-button">
                    <h2>Project Submission</h2>
                </div>
                <hr/ id="line">
                <?php $project->printChapters(); ?>
            </section>
        </section>
    </main>
</body>
</html>
<?php
    require_once '../MIDDLEWARE/db_connect.php';
    require_once "../MIDDLEWARE/role-state-management.php";
    roleStateManagement("Student");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/submit.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Individual Proposal Form</title>
</head>

<body>
    <?php include '../HEADER/student-partial-header.inc.php'; ?>
    <?php
        require_once '../MIDDLEWARE/db_connect.php';
        include '../GET/func-submit.php';
    ?>

    <main>
        <h1>Individual Proposal Form</h1>
        <!-- Radio Buttons for Toggling -->
        <input type="radio" name="form-toggle" id="student-propose" checked>
        <input type="radio" name="form-toggle" id="supervisor-propose">

        <nav id="form-toggle">
            <label for="student-propose">Student Propose</label>
            <label for="supervisor-propose">Supervisor Propose</label>
        </nav>
        <section>
            <form id="student-propose-form" action="../POST/project-submitted.php" method="POST">
                <div id="form-input">
                    <input type="text" name="title" id="title" placeholder="Project Title" required>
                    <select name="project-type" id="project-type" required>
                        <option disabled selected>Project Type</option>
                        <option value="Application">Application-Based</option>
                        <option value="Research">Research-Based</option>
                        <option value="Mixed">Application & Research Based</option>
                    </select>
                    <textarea name="description" id="description" placeholder="Project Description" required></textarea>
                    <textarea name="motivation" id="motivation" placeholder="Motivation" required></textarea>
                    <textarea name="objective" id="objective" placeholder="Project Scope & Objective" required></textarea>
                    <textarea name="propose" id="propose" hidden>Student-Propose</textarea>
                    <select name="supervisor" id="supervisor">
                        <option value="" disabled selected>Supervisor</option>
                        <?php displaySupervisorByName($supervisorRows) ?>
                    </select>
                </div>
                <div id="file-upload">
                    <input type="file" name="proposal-1" id="proposal-1" accept=".pdf" required>
                </div>
                <input type="submit" name="submit" value="Submit">
            </form>

            <form id="supervisor-propose-form" action="../POST/project-submitted.php" method="POST">
                <div>
                    <select name="supervisor" id="supervisor-id" onchange="filterProjects()" required>
                        <option value="" disabled selected>Supervisor</option>
                        <?php displaySupervisorById($supervisorRows)?>
                    </select>
                    <select name="title" id="title-name" onchange="filterSupervisor()" required>
                        <option value="" disabled selected>Project Title</option>
                        <?php displayProject($projectRows) ?>
                    </select>
                    <select name="project-type2" id="project-type2" required>
                        <option disabled selected>Project Type</option>
                        <option value="Application">Application-Based</option>
                        <option value="Research">Research-Based</option>
                        <option value="Mixed">Application & Research Based</option>
                    </select>
                    <textarea name="description" id="description" placeholder="Project Description" required></textarea>
                    <textarea name="motivation" id="motivation" placeholder="Motivation" required></textarea>
                    <textarea name="objective" id="objective" placeholder="Project Scope & Objective" required></textarea>
                    <textarea name="propose" id="propose" hidden>Supervisor-Propose</textarea>
                </div>
                
                <div id="file-upload">
                    <input type="file" name="proposal-1" id="proposal-1" accept=".pdf" required>
                </div>
                <input type="submit" name="submit" value="Submit">
            </form>
        </section>
    </main>
</body>

</html>
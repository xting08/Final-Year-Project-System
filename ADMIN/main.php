<?php
require_once("../MIDDLEWARE/db_connect.php");
require_once("../MIDDLEWARE/role-state-management.php");
roleStateManagement("Admin");
$conn = OpenCon();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Main Page</title>
</head>

<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>

    <main>
        <article id="announcement">
            <h3><i class="fa-solid fa-bullhorn"></i> | Recent Announcement</h3>
            <hr/>
            <ul>
            <?php
                $sql = "SELECT title, author, posted_date FROM announcement ORDER BY posted_date DESC LIMIT 3";
                if ($result = mysqli_query($conn, $sql)) {
                    if (mysqli_num_rows($result) > 0) { 
                    while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li>";
                            echo "<a href='announcement.php'>";

                            echo "<p><span>" .($row['author']). "</span> | <time datetime='" .$row['posted_date']. "'>" .$row['posted_date']. "</time></p>";
                            echo "<h4>" .($row['title']). "</h4>";
                            echo "</a>";
                            echo "</li>";
                        }
                    } 
                    else {
                        echo "<li><p>No recent announcements.</p></li>";
                    }
                }
            ?>
            </ul>
        </article>

        <nav id="quick-access">
            <button onclick="window.location.href='chat.php'">
                <i class="fa-solid fa-comments"></i>
                <h2>CHAT ROOM</h2>
            </button>
            <button onclick="window.location.href='project-management.php'">
                <i class="fa-solid fa-file-pen"></i>
                <h2>MANAGE PROPOSAL</h2>
            </button>
            <button onclick="window.location.href='user-profile-management.php'">
                <i class="fa-solid fa-user-gear"></i>
                <h2>MANAGE USER</h2>
            </button>
            <button onclick="window.location.href='user.php'">
                <i class="fa-solid fa-user"></i>
                <h2>USER PROFILE</h2>
            </button>
            <button onclick="window.location.href='support.php'">
                <i class="fa-solid fa-life-ring"></i>
                <h2>SERVICE & SUPPORT</h2>
            </button>
        </nav>
    </main>
</body>

</html>
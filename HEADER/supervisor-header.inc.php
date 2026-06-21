<?php
    require_once '../MIDDLEWARE/db_connect.php';
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $connect = OpenCon();
    $query = "SELECT full_name FROM supervisor WHERE user_id = " . $_SESSION['user_id'];
    $result = mysqli_query($connect, $query);
    $row = $result -> fetch_assoc();
?>

<header>
    <div id="menu">
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">
            <i class="fa-solid fa-bars menu-bar"></i>
        </label>
        <nav id="ham-menu">
            <div>
                <label for="menu-toggle"><i class="fa-regular fa-circle-xmark close-menu"></i></label>
                <ul>
                    <li><a href="main.php">HOME</a></li>
                    <li><a href="announcement.php">ANNOUNCEMENT</a></li>
                    <li><a href="chat.php">CHAT ROOM</a></li>
                    <li><a href="meeting.php">MEETING MANAGEMENT</a></li>
                    <li><a href="project-management.php">MANAGE PROPOSAL</a></li>
                    <li><a href="progression.php">STUDENT PROGRESSION</a></li>
                    <li><a href="marksheet.php">MARKSHEET</a></li>
                </ul>
            </div>
            <div>
                <ul>
                    <li><a href="user.php">USER PROFILE</a></li>
                    <li><a href="support.php">SERVICE & SUPPORT</a></li>
                    <li><a href="group-member.php">GROUP MEMBERS INFO</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <figure id="logo">
        <a href="main.php"><img src="../IMG/MMU-logo.png" alt="MMU"></a>
    </figure>

    <div id="user">
        <h3>WELCOME, <span><?php echo $row['full_name'] ?></span></h3>
        <button id="logout-btn" onclick="window.location.href='../login.php'">LOGOUT</button>
    </div>
</header>
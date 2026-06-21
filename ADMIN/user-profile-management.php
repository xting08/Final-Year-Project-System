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
    <link rel="stylesheet" href="../CSS/user-profile-management.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>User Profile Management</title>
</head>

<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>

    <main>
        <nav id="sort-search">
            <div id="add-user">
                <button onclick=" window.location.href='register-admin.php'">
                    <i class="fa-solid fa-user-plus"></i>
                    ADD USER</button>
            </div>
            <div id="search-user">
                <select name="actor" id="actor" onchange="window.location.href='user-profile-management.php?actor=' + this.value">
                    <option value="none" selected disabled>Sort by Actor</option>
                    <option value="student"<?php echo isset($_GET['actor']) && $_GET['actor'] == 'student' ? 'selected' : ''; ?>>Student</option>
                    <option value="supervisor"<?php echo isset($_GET['actor']) && $_GET['actor'] == 'supervisor' ? 'selected' : ''; ?>>Supervisor</option>
                    <option value="admin"<?php echo isset($_GET['actor']) && $_GET['actor'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <form method="GET" action="user-profile-management.php">
                    <input type="text" id="search" name="search" placeholder="Search User" required>
                    <button type="submit" id="search-btn">SEARCH <i class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </nav>

        <?php
            if(isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM users WHERE full_name LIKE '%$search%'";
            }

            else if (isset($_GET['actor'])) {
                $actor = $_GET['actor'];
                $sql = "SELECT * FROM users WHERE role = '$actor'";
            }
            
            else {
                $sql = "SELECT * FROM users";
            }

            if ($result = mysqli_query($conn, $sql)) {
                echo "<section class='user-line'>"; 
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='user-card'>";
                    echo "<h3>".$row['full_name']."</h3>";
                    echo "<hr/>";
                    echo "<p>".$row['user_id']."</p>";
                    echo "<p>".$row['role']."</p>";
                    echo "<button onclick=\"window.location.href='manage-profile.php?id=".$row['user_id']."'\">VIEW</button>";
                    echo "</div>";
                }
                echo "</section>";
            } 
            else {
                echo "<p>No users found.</p>";
            }
        ?>
    </main>
</body>

</html>
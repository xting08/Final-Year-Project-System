<?php
require_once '../MIDDLEWARE/db_connect.php';
require_once "../MIDDLEWARE/role-state-management.php";
roleStateManagement("Admin");
$conn = OpenCon();

function outputProfile($name, $role, $id, $email) {
    echo "<tr>";
    echo "<td><label for='full-name'>Full Name</label></td>";
    echo "<td>";
    echo "<div class='editable'>";
    echo "<input type='text' name='full-name' id='full-name' value='$name' />";
    echo "<i class='fas fa-pen'></i>";
    echo "</div>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><label for='role'>Role</label></td>";
    echo "<td><input type='text' name='role' id='role' value='$role' readonly /></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><label for='id'>Student ID / User ID</label></td>";
    echo "<td>";
    echo "<div class='editable'>";
    echo "<input type='text' name='id' id='id' value='$id' />";
    echo "<i class='fas fa-pen'></i>";
    echo "</div>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><label for='email'>Email Address</label></td>";
    echo "<td>";
    echo "<div class='editable'>";
    echo "<input type='text' name='email' id='email' value='$email' />";
    echo "<i class='fas fa-pen'></i>";
    echo "</div>";
    echo "</td>";
    echo "</tr>";
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/set.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/profile.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="../JS/profile.js" ></script>
    <title>User Profile Management</title>
</head>

<body>
    <?php include '../HEADER/admin-header.inc.php'; ?>

    <div class="container">
        <div class="header">
            <h2>PROFILE MANAGEMENT</h2>
        </div>
    
        <div class="profile-container">
            <div class="col-left">
                <p>
                    <img src="../IMG/profile.jpg" class="profile-img" alt="Profile Image"></img>
                </p>
                <p>
                    <label for="photo" class="upload-btn">Upload Photo</label>
                    <input type="file" name="photo" id="photo" hidden />
                </p>
            </div>

            <?php
                $id = $_GET['id'];
                $userSql = "SELECT * FROM users WHERE user_id = '$id'";
                $userResult = mysqli_query($conn, $userSql);
                $userRow = mysqli_fetch_assoc($userResult);
                $name = $userRow['full_name'];
                $role = $userRow['role'];
                $id = $userRow['user_id'];
            ?>

            <div class="col-right">
                <form id="profile-form" action="../POST/admin-manage-profile.php" method="POST" onsubmit=""> 
                    <table class="profile-table">
                        <?php
                            if($role == 'Student') {
                                $studSql = "SELECT * FROM student WHERE user_id = '$id'";
                                $studResult = mysqli_query($conn, $studSql);
                                $studRow = mysqli_fetch_assoc($studResult);
                                $email = $studRow['email'];
                                $spec = $studRow['specialisation'];
                                $projSql = "SELECT * FROM project WHERE student_id = '$id'";
                                $projResult = mysqli_query($conn, $projSql);
                                $projRow = mysqli_fetch_assoc($projResult);
                                $title = $projRow['title'] ?? '-' ;
                                outputProfile($name, $role, $id, $studRow['email']);

                                echo "<tr>";
                                echo "<td><label for='specialisation'>Specialisation</label></td>";
                                echo "<td>";
                                echo "<div class='editable'>";
                                echo "<input type='text' name='specialisation' id='specialisation' value='$spec' />";
                                echo "<i class='fas fa-pen'></i>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td><label for='title'>Project Title</label></td>";
                                echo "<td><input type='text' name='title' id='title' value='$title' readonly /></td>";
                                echo "</tr>";
                            }

                            else if($role == 'Admin') {
                                $adminSql = "SELECT * FROM admin WHERE user_id = '$id'";
                                $adminResult = mysqli_query($conn, $adminSql);
                                $adminRow = mysqli_fetch_assoc($adminResult);
                                $email = $adminRow['email'];
                                outputProfile($name, $role, $id, $email);
                            }

                            else {
                                $supSql = "SELECT * FROM supervisor WHERE user_id = '$id'";
                                $supResult = mysqli_query($conn, $supSql);
                                $supRow = mysqli_fetch_assoc($supResult);
                                $email = $supRow['email'];
                                outputProfile($name, $role, $id, $email);
                            } 
                           
                        ?>
                        
                    </table>

                    <div id="btn-area">
                        <input type="submit" id="submit" name="submit" value="Save Changes" />
                        <input type="submit" id="remove" name="remove" value="Remove User" onclick="return confirm('Confirm to remove this user?');" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
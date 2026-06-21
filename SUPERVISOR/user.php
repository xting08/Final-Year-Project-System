<?php
    require_once("../MIDDLEWARE/db_connect.php");
    require_once("../MIDDLEWARE/role-state-management.php");
    roleStateManagement("Supervisor");
    $conn = OpenCon();
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<script>alert('Password successfully updated!');</script>";
        echo "<script>window.location.href = 'user.php';</script>";
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
    <title>Personal Profile Management</title>
</head>

<body>
    <?php include '../HEADER/supervisor-header.inc.php'; ?>

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
                $id = $_SESSION['user_id'];
                $supSql = "SELECT * FROM supervisor WHERE user_id = '$id'";
                $supResult = mysqli_query($conn, $supSql);
                $supRow = mysqli_fetch_assoc($supResult);
                $name = $supRow['full_name'];
                $role = $supRow['role'];
                $id = $supRow['user_id'];
                $email = $supRow['email'];
            ?>

            <div class="col-right">
                <form id="profile-form" action="../POST/profile-manage.php" method="POST" onsubmit="return validatePassword()"> 
                    <table class="profile-table">
                        <tr>
                            <td><label for="full-name">Full Name</label></td>
                            <td>
                                <div class="editable">
                                    <input type="text" name="full-name" id="full-name" value=<?php echo $name ?> required />
                                    <i class='fas fa-pen'></i>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="role">Role</label></td>
                            <td><input type="text" name="role" id="role" value=<?php echo $role ?> required /></td>
                        </tr>
                        <tr>
                            <td><label for="id">Student ID / User ID</label></td>
                            <td><input type="text" name="id" id="id" value=<?php echo $id ?> required /></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email Address</label></td>
                            <td><input type="text" name="email" id="email" value=<?php echo $email ?> required /></td>
                        </tr>
                        <tr>
                            <td><label for="reset-password">Reset Password ?</label></td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" id="toggle-reset" onclick="showResetArea()"/>
                                    <span class="slider round"></span> 
                                </label>
                            </td>
                            <td></td>
                        </tr>
                        <tr id="reset-area" style="display: none;">
                            <td><label for="new-password">New Password</label></td>
                            <td>
                                <div class="change-password">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" name="new-password" id="new-password" placeholder="New Password" oninput="enableSubmit()" />
                                </div>
                            </td>
                        </tr>
                        <tr id="confirm-reset-area" style="display: none;">
                            <td><label for="confirm-password">Confirm Password</label></td>
                            <td>
                                <div class="change-password">
                                    <i class="fa fa-lock"></i>
                                    <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" oninput="enableSubmit()" />
                                </div>
                            </td>
                        </tr>
                        <tr id="show-password-area" style="display: none;">
                            <td></td>
                            <td>
                                <input type="checkbox" id=show-password onclick="showPassword()"/>
                                <label for="show-password" style="font-size: 12px;">Show Password</label>
                            </td>
                        </tr>
                    </table>

                    <div id="save-changes-area" style="display: none;">
                        <input type="submit" id="submit" name="submit" value="Save Changes" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
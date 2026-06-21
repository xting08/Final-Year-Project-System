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
    <link rel="stylesheet" href="../CSS/register.css">
    <link rel="icon" href="../IMG/favicon.png" type="image/ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>User Registration Page</title>
</head>
<body>
    <div id="container">
        <button type="none" id="back-button" onclick="window.location.href='user-profile-management.php'"><i class="fa-solid fa-angle-left"></i>BACK</button>

        <header id="title">
            <h1 class="FCI">Admin User Management</h1>
            <h1 class="FYP">User Registration Page</h1>
        </header>
        
        <section id="form">
            <!-- action and method need to be add on part 2 -->
            <form id="register-form" action="../POST/register-user.php" method="post"> 
                <input type="text" name="full-name" id="full-name" placeholder="Full Name" required>
                <input type="text" name="user-id" id="user-id" placeholder="User ID" required>
                <select name="role" id="role" required>
                    <option disabled selected>User Role</option>
                    <option value="admin">Admin</option>
                    <option value="supervisor">Supervisor</option>
                </select>
                <input type="text" name="phone" id="phone" placeholder="Phone No." required>
                <input type="text" name="email" id="email" placeholder="Email Address" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="c-password" id="c-password" placeholder="Confirm Password" required>
                <!-- button type need to be change on part 2 -->
                <button type="submit" id="register-button" name="submit" onclick="window.location.href='user-profile-management.php'">Register</button>
            </form>
        </section>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/set.css">
    <link rel="stylesheet" href="CSS/login.css">
    <link rel="icon" href="IMG/favicon.png" type="image/ico">
    <title>Login Page</title>
</head>

<body>
    <div id="container">
        <header id="title">
            <h1 class="FCI">Faculty of Computing Informatic</h1>
            <h1 class="FYP">Final Year Project System</h1>
        </header>

        <section id="form">
            <form id="login-form" action="MIDDLEWARE/dynamic-login.php" method="POST">
                <h2>Login</h2>
                <input type="text" name="user_id" id="user-id" placeholder="User ID" required>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <button type="submit" id="login-button">Login</button>

                <p class="register">Don't have an account? <a href="register.php">Register</a></p>
            </form>
            </section>

    </div>
</body>

</html>
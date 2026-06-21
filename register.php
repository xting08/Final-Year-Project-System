<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/set.css">
    <link rel="stylesheet" href="CSS/register.css">
    <link rel="icon" href="IMG/favicon.png" type="image/ico">
    <title>User Registration Page</title>
</head>
<body>
    <div id="container">
        <header id="title">
            <h1 class="FCI">Faculty of Computing Informatic</h1>
            <h1 class="FYP">Final Year Project System</h1>
        </header>
        
        <section id="form">
            <!-- action and method need to be add on part 2 -->
            <form id="register-form" action="POST/register-student.php" method="post"> 
                <h2>Register for FYP</h2>
                <input type="text" name="full-name" id="full-name" placeholder="Full Name" required>
                <input type="text" name="student-id" id="student-id" placeholder="Student ID" required>
                <select name="is-collab" id="is-collab" required>
                    <option value="0">Individual</option>
                    <option value="1">Collaboration</option>
                </select>
                <select name="specialisation" id="specialisation" required>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Computer Network">Computer Network</option>
                    <option value="Game Development">Game Development</option>
                    <option value="Cyber Security">Cyber Security</option>
                </select>

                <input type="text" name="phone" id="phone" placeholder="Phone No." required>
                <input type="text" name="email" id="email" placeholder="Email Address" required>
                <input type="password" name="password" id="password" placeholder="Password" required>

                <input type="password" name="c-password" id="c-password" placeholder="Confirm Password" required>
                <button type="submit" id="submit" name="submit">Register</button>
                <p class="register">Already have an account? <a href="login.php">Login</a> </p>
            </form>
        </section>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<html>
    <head> 
        <title>Login</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
        <!-- <script src="login.js"></script> -->
    </head>
<body>>
    <h1> Login </h1>

    <form id="login-form" action = "login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" required>

        <button type="button" onclick = "window.location.href = home.php" >Cancel</button>
        <button type="button"" >Login</button>
        <!-- Need to do validation to database to make sure the user is in the database and what type of user they are.  -->
    </form>

</body>
</html>

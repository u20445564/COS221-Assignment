<!DOCTYPE html>
<html lang="en">
<html>
    <head> 
        <title>Register</title>
        <!-- <link rel="stylesheet" href="style.css"> -->
        <!-- <script src="login.js"></script> -->
    </head>

<h1>Register</h1>

<form id="register-form" action = "register.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" required>

    <label for="surname">Surname:</label>
    <input type="text" id="surname" required>

    <label for ="username">Username:</label>
    <input type="text" id="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" required>

    <label for="phoneNumber">Phone Number:</label>
    <input type="text" id="phoneNumber" required>

    <label for="password">Password:</label>
    <input type="password" id="password" required>

    <label for="confirm-password">Confirm Password:</label>
    <input type="password" id="confirm-password" required>

    <button type="button" onclick = "window.location.href = home.php" >Cancel</button>
    <button type="button" onclick = "window.location.href = login.php" >Register</button>
</form>

</body>
</html>
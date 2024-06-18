<?php
ini_set("display_errors", 1);
include("funcs.php");
session_start();

$username = $password = "";
$usernameErr = $passwordErr = $loginErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($usernameErr) && empty($passwordErr)) {
        if (login_user($username, $password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("Location: welcome.php");
            exit;
        } else {
            $loginErr = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>ログイン</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username);?>">
            <span class="error"><?php echo $usernameErr;?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <span class="error"><?php echo $passwordErr;?></span>
        </div>
        <div class="form-group">
            <button type="submit">ログイン</button>
        </div>
        <span class="error"><?php echo $loginErr;?></span>
    </form>
    <p>アカウントをお持ちではありませんか？ <a href="register.php">こちら</a></p>
</div>
</body>
</html>
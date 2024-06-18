<?php
ini_set("display_errors", 1);
include("funcs.php");

$username = $password = "";
$usernameErr = $passwordErr = $registerErr = "";

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
        // Check if username already exists
        if (user_exists($username)) {
            $registerErr = "Username already taken";
        } else {
            // Register the user
            register_user($username, $password);
            redirect('upload.php'); // リダイレクト
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./register.css">
</head>
<body>
<div class="login-container">
    <h2>新規登録</h2>
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
            <button type="submit">新規登録</button>
        </div>
        <span class="error"><?php echo $registerErr;?></span>
    </form>
    <p>すでにアカウントをお持ちですか? <a href="login.php">こちら</a></p>
</div>
</body>
</html>
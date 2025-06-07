<?php
session_start();

if (isset($_SESSION['username']) || isset($_COOKIE['username'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    $valid_user = "admin";
    $valid_pass = "123";

    if ($input_user === $valid_user && $input_pass === $valid_pass) {
        $_SESSION['username'] = $input_user;

        if (isset($_POST['remember'])) {
            setcookie("username", $input_user, time() + (86400 * 7), "/"); // 7 hari
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h2>Form Login</h2>
    <?php if (!empty($error))
        echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <label><input type="checkbox" name="remember"> Remember me</label><br><br>
        <input type="submit" value="Login">
    </form>
</body>

</html>
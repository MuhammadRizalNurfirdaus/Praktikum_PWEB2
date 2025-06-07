<?php
session_start();

if (!isset($_SESSION['username']) && !isset($_COOKIE['username'])) {
    header("Location: login.php");
    exit();
}

$user = isset($_SESSION['username']) ? $_SESSION['username'] : $_COOKIE['username'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>
    <h2>Selamat datang, <?php echo htmlspecialchars($user); ?>!</h2>
    <p><a href="logout.php">Logout</a></p>
</body>

</html>
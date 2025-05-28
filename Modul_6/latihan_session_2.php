<html>

<head>
    <title>LATIHAN SESSION</title>
</head>
<?php
if (!isset($user)) {
    die("Anda Belum Register atau Mendaftar");
} else {
    echo "<p>
    <a href=keluar_session.php>Logout</a>";
    echo "<h1>Welcome To My Website, $user</h1>";
}
?>

<body>
</body>

</html>
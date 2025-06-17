<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Silakan Login</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="color: red;"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/login" method="post">
        <?= csrf_field() ?>
        <label for="username">Username</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>

</html>
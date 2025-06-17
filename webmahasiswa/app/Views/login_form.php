<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial; background: #f0f0f0;
            display: flex; justify-content: center; align-items: center;
            height: 100vh; margin: 0;
        }
        form {
            background: #fff; padding: 25px; width: 320px;
            border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        h3 {
            text-align: center; margin-bottom: 20px;
        }
        input, button {
            width: 100%; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background: #007bff; color: #fff; border: none;
            font-weight: bold; cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .error {
            color: red; font-size: 13px; background: #ffe6e6;
            padding: 6px; border-radius: 5px; text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <form action="<?= base_url('/auth/prosesLogin') ?>" method="post">
        <h3>Login</h3>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
    </form>
</body>
</html>

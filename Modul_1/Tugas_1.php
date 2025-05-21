<!DOCTYPE html>
<html>
<head>
    <title>Menghitung Luas Lingkaran</title>
</head>
<body>
    <h2>Hitung Luas Lingkaran</h2>
    <form method="post">
        Masukkan jari-jari (r): <input type="text" name="jari" required>
        <input type="submit" name="submit" value="Hitung">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $jari = $_POST['jari'];
        $phi = 3.14;

        if (is_numeric($jari) && $jari > 0) {
            $luas = $phi * $jari * $jari;
            echo "<p>Luas lingkaran dengan jari-jari $jari adalah: <strong>$luas</strong></p>";
        } else {
            echo "<p>Masukkan jari-jari yang valid (angka positif).</p>";
        }
    }
    ?>
</body>
</html>

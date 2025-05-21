<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postest</title>
</head>

<body>
    <?php
    $jumlah = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
    $nama_array = [];
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tampilkan'])) {
        for ($i = 0; $i < $jumlah; $i++) {
            $nama = trim($_POST['nama'][$i] ?? '');
            if (empty($nama) || is_numeric($nama)) {
                $errors[] = "Nama ke-" . ($i + 1) . " tidak valid (tidak boleh angka atau kosong)";
            } else {
                $nama_array[] = $nama;
            }
        }
        sort($nama_array);
    }
    ?>

    <h1>Form Input Nama</h1>

    <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' || isset($_POST['input'])): ?>
        <form method="POST">
            <label for="jumlah">Jumlah Nama:</label>
            <input type="number" id="jumlah" name="jumlah" value="<?= htmlspecialchars($jumlah) ?>" required>
            <button type="submit" name="input">Input Data</button>
        </form>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $jumlah > 0 && !isset($_POST['tampilkan'])): ?>
        <form method="POST">
            <input type="hidden" name="jumlah" value="<?= htmlspecialchars($jumlah) ?>">
            <p>Inputkan Nama:</p>
            <?php for ($i = 0; $i < $jumlah; $i++): ?>
                <label for="nama_<?= $i ?>">Nama [<?= $i + 1 ?>]:</label>
                <input type="text" id="nama_<?= $i ?>" name="nama[]"><br>
            <?php endfor; ?>
            <button type="submit" name="tampilkan">Tampilkan</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($errors) || !empty($nama_array)): ?>
        <div>
            <h3>Hasil:</h3>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $err): ?>
                    <p><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (empty($errors) && !empty($nama_array)): ?>
                <?php foreach ($nama_array as $index => $nama): ?>
                    <p>Nama [<?= $index + 1 ?>] = <?= htmlspecialchars($nama) ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>

</html>
<?php
spl_autoload_register(function ($class_name) {
    if (file_exists($class_name . '.php')) {
        require_once $class_name . '.php';
    }
});

$hasil = null;
$bangunDatar = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pilihan = $_POST['bangun_datar'];

    try {
        switch ($pilihan) {
            case 'persegi':
                $sisi = (float)$_POST['sisi'];
                if ($sisi <= 0) throw new Exception("Nilai sisi harus positif.");
                $bangunDatar = new Persegi($sisi);
                break;

            case 'lingkaran':
                $jariJari = (float)$_POST['jari_jari'];
                if ($jariJari <= 0) throw new Exception("Nilai jari-jari harus positif.");
                $bangunDatar = new Lingkaran($jariJari);
                break;

            case 'segitiga':
                $alas = (float)$_POST['alas'];
                $tinggi = (float)$_POST['tinggi'];
                if ($alas <= 0 || $tinggi <= 0) throw new Exception("Nilai alas dan tinggi harus positif.");
                $bangunDatar = new Segitiga($alas, $tinggi);
                break;

            default:
                throw new Exception("Pilihan tidak valid.");
        }

        if ($bangunDatar) {
            $hasil = [
                'nama' => $bangunDatar->getNama(),
                'luas' => $bangunDatar->hitungLuas(),
                'keliling' => $bangunDatar->hitungKeliling(),
            ];
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Bangun Datar OOP</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        select,
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .result,
        .error {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }

        .result {
            background-color: #e2f0d9;
            border: 1px solid #b6d7a8;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .input-group {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Kalkulator Bangun Datar OOP</h1>
        <form action="index.php" method="post" id="kalkulatorForm">
            <div>
                <label for="bangun_datar">Pilih Bangun Datar:</label>
                <select name="bangun_datar" id="bangun_datar" onchange="showInputs()">
                    <option value="">-- Pilih --</option>
                    <option value="persegi">Persegi</option>
                    <option value="lingkaran">Lingkaran</option>
                    <option value="segitiga">Segitiga</option>
                </select>
            </div>

            <div id="input-persegi" class="input-group">
                <label for="sisi">Sisi:</label>
                <input type="number" name="sisi" id="sisi" step="any">
            </div>

            <div id="input-lingkaran" class="input-group">
                <label for="jari_jari">Jari-jari:</label>
                <input type="number" name="jari_jari" id="jari_jari" step="any">
            </div>

            <div id="input-segitiga" class="input-group">
                <label for="alas">Alas:</label>
                <input type="number" name="alas" id="alas" step="any">
                <label for="tinggi" style="margin-top:10px;">Tinggi:</label>
                <input type="number" name="tinggi" id="tinggi" step="any">
            </div>

            <button type="submit">Hitung</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="error">
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($hasil): ?>
            <div class="result">
                <h2>Hasil Perhitungan</h2>
                <p><strong>Bangun Datar:</strong> <?= htmlspecialchars($hasil['nama']) ?></p>
                <p><strong>Luas:</strong> <?= number_format($hasil['luas'], 2) ?></p>
                <p><strong>Keliling:</strong> <?= number_format($hasil['keliling'], 2) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function showInputs() {
            document.getElementById('input-persegi').style.display = 'none';
            document.getElementById('input-lingkaran').style.display = 'none';
            document.getElementById('input-segitiga').style.display = 'none';

            const pilihan = document.getElementById('bangun_datar').value;
            if (pilihan) {
                document.getElementById('input-' + pilihan).style.display = 'block';
            }
        }
        showInputs();
    </script>

</body>

</html>

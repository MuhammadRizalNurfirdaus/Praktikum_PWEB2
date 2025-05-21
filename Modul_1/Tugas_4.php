<!DOCTYPE html>
<html>
<head>
    <title>Perhitungan Gaji Karyawan</title>
</head>
<body>
    <h2>PERHITUNGAN GAJI KARYAWAN</h2>
    <form method="post">
        Golongan :
        <select name="golongan">
            <option value="I">I</option>
            <option value="II">II</option>
            <option value="III">III</option>
        </select>
        <br><br>
        <input type="submit" name="hitung" value="HITUNG">
    </form>

    <?php
function hitungGaji($golongan) {
    switch ($golongan) {
        case 'I':
            $gapok = 500000;
            $tunjangan = 175000;
            $potongan = 0.10 * $gapok;
            break;
        case 'II':
            $gapok = 750000;
            $tunjangan = 155000;
            $potongan = 0.075 * $gapok;
            break;
        case 'III':
            $gapok = 1000000;
            $tunjangan = 146000;
            $potongan = 0.05 * $gapok;
            break;
        default:
            return "Golongan tidak valid";
    }

    $totalGaji = $gapok + $tunjangan - $potongan;

    return [
        'gapok' => $gapok,
        'tunjangan' => $tunjangan,
        'potongan' => $potongan,
        'total' => $totalGaji
    ];
}

if (isset($_POST['hitung'])) {
    $golongan = $_POST['golongan'];
    $hasil = hitungGaji($golongan);
}
?>

    <?php if (isset($hasil)) { ?>
        <p>Gaji Pokok : <?= number_format($hasil['gapok'], 0, ',', '.') ?></p>
        <p>Tunjangan : <?= number_format($hasil['tunjangan'], 0, ',', '.') ?></p>
        <p>Potongan : <?= number_format($hasil['potongan'], 0, ',', '.') ?></p>
        <p><strong>Total Gaji : <?= number_format($hasil['total'], 0, ',', '.') ?></strong></p>
    <?php } ?>
</body>
</html>

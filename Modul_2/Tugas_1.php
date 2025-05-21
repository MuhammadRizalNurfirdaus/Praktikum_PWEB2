<!DOCTYPE html>
<html>

<head>
    <title>Penghitungan Total dan Rata-Rata</title>
</head>

<body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $angka = $_POST['angka'];
        $jumlah = count($angka);

        sort($angka);
        $total = array_sum($angka);
        $rata = $total / $jumlah;

        echo "<h3>Hasil Pengurutan dan Perhitungan:</h3>";
        echo "<div style='border:1px solid black; padding:10px; width:fit-content;'>";
        for ($i = 0; $i < $jumlah; $i++) {
            echo "Angka[" . ($i + 1) . "] = " . $angka[$i] . "<br>";
        }
        echo "Total = $total<br>";
        echo "Rata-Rata = $rata";
        echo "</div>";
    } elseif (isset($_GET['jumlah'])) {
        $jumlah = intval($_GET['jumlah']);
        ?>
        <form method="post" action="">
            <h3>Inputkan Bilangan :</h3>
            <?php
            for ($i = 0; $i < $jumlah; $i++) {
                echo "Angka[" . ($i + 1) . "] = <input type='number' name='angka[]' required><br>";
            }
            ?>
            <input type="submit" name="submit" value="Tampil">
        </form>
        <?php
    } else {
        ?>
        <form method="get" action="">
            <h3>Masukkan Jumlah Data:</h3>
            Jumlah Data: <input type="number" name="jumlah" required>
            <input type="submit" value="INPUT DATA">
        </form>
        <?php
    }
    ?>

</body>

</html>
<!DOCTYPE html>
<html>

<head>
    <title>Form Peminjaman Buku</title>
</head>

<body>
    <h2>Input Data Peminjaman</h2>
    <form method="post" action="">
        Kode Pinjam: <input type="text" name="KODE_PINJAM" required><br><br>
        Tanggal Pinjam: <input type="date" name="TGL_PINJAM" required><br><br>
        NIM Mahasiswa:
        <select name="NIM" required>
            <option value="">--Pilih NIM--</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "perpustakaan_fkom");
            $mhs = mysqli_query($conn, "SELECT NIM, NAMA FROM mahasiswa");
            while ($row = mysqli_fetch_assoc($mhs)) {
                echo "<option value='{$row['NIM']}'>{$row['NIM']} - {$row['NAMA']}</option>";
            }
            ?>
        </select><br><br>

        Kode Buku:
        <select name="kode_buku" required>
            <option value="">--Pilih Kode Buku--</option>
            <?php
            $buku = mysqli_query($conn, "SELECT KD_BUKU, JUDUL FROM buku");
            while ($row = mysqli_fetch_assoc($buku)) {
                echo "<option value='{$row['KD_BUKU']}'>{$row['KD_BUKU']} - {$row['JUDUL']}</option>";
            }
            ?>
        </select><br><br>

        Tanggal Kembali: <input type="date" name="tgl_kembali" required><br><br>
        <input type="submit" name="submit" value="Simpan">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $kode_pinjam = $_POST['KODE_PINJAM'];
        $tgl_pinjam = $_POST['TGL_PINJAM'];
        $nim = $_POST['NIM'];
        $kode_buku = $_POST['kode_buku'];
        $tgl_kembali = $_POST['tgl_kembali'];

        $sql = "INSERT INTO peminjaman (kode_pinjam, tanggal_pinjam, nim, kode_buku, tanggal_kembali)
                VALUES ('$kode_pinjam', '$tgl_pinjam', '$nim', '$kode_buku', '$tgl_kembali')";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Data peminjaman berhasil disimpan!</p>";
        } else {
            echo "<p>Error: " . mysqli_error($conn) . "</p>";
        }
    }
    mysqli_close($conn);
    ?>
</body>

</html>
<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "perpustakaan_fkom");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses insert data (Ditaruh di atas sebelum HTML)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Cegah error "undefined index"
    $nim = $_POST['nim'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $fakultas = $_POST['fakultas'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';
    $jenjang = $_POST['jenjang'] ?? '';

    $sql_insert = "INSERT INTO mahasiswa (nim, nama, fakultas, jurusan, jenjang) 
                   VALUES ('$nim', '$nama', '$fakultas', '$jurusan', '$jenjang')";
    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('Data berhasil disimpan'); window.location.href='tugas4.php';</script>";
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Mahasiswa</title>
</head>

<body>
    <h2>Form Input Mahasiswa</h2>
    <form method="post" action="">
        NIM: <input type="text" name="nim" required><br><br>
        Nama: <input type="text" name="nama" required><br><br>
        Fakultas: <input type="text" name="fakultas" required><br><br>
        Jurusan: <input type="text" name="jurusan" required><br><br>
        Jenjang: <input type="text" name="jenjang" required><br><br>
        <input type="submit" name="submit" value="Simpan">
    </form>

    <h2>Data Mahasiswa</h2>
    <table border="1">
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Jenjang</th>
        </tr>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            $nim = $_POST['nim'] ?? '';
            $nama = $_POST['nama'] ?? '';
            $fakultas = $_POST['fakultas'] ?? '';
            $jurusan = $_POST['jurusan'] ?? '';
            $jenjang = $_POST['jenjang'] ?? '';

            $sql_insert = "INSERT INTO mahasiswa (nim, nama, fakultas, jurusan, jenjang) 
                   VALUES ('$nim', '$nama', '$fakultas', '$jurusan', '$jenjang')";

            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Data berhasil disimpan'); window.location.href='tugas4.php';</script>";
                exit;
            } else {
                echo "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }

        mysqli_close($conn);
        ?>
    </table>
</body>

</html>
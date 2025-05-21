<!DOCTYPE html>
<html>
<head>
    <title>Form Biodata</title>
</head>
<body>

<h2>Form Biodata</h2>
<form method="post" action="">
    Nama: <input type="text" name="nama"><br><br>
    Umur: <input type="number" name="umur"><br><br>
    Jenis Kelamin:
    <select name="gender">
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select><br><br>
    Alamat: <textarea name="alamat"></textarea><br><br>
    <input type="submit" name="submit" value="Tampilkan Biodata">
</form>

<?php
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $gender = $_POST['gender'];
    $alamat = $_POST['alamat'];

    echo "<h3>Biodata</h3>";
    echo "Nama: $nama <br>";
    echo "Umur: $umur tahun<br>";
    echo "Jenis Kelamin: $gender<br>";
    echo "Alamat: $alamat<br>";
}
?>

</body>
</html>

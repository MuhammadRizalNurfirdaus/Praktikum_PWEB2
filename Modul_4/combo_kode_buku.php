<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "perpustakaan");

// ambil data semua kode buku
$query = "SELECT * FROM buku";
$result = mysqli_query($conn, $query);
?>

<h2>Pilih Kode Buku</h2>
<form method="post" action="">
    <label>Kode Buku:</label>
    <select name="kode_buku" onchange="this.form.submit()">
        <option value="">--Pilih Salah Satu--</option>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $selected = (isset($_POST['kode_buku']) && $_POST['kode_buku'] == $row['KD_BUKU']) ? 'selected' : '';
            echo "<option value='{$row['KD_BUKU']}' $selected>{$row['KD_BUKU']}</option>";
        }
        ?>
    </select>

    <br><br>

    <label>Judul Buku:</label>
    <input type="text" name="judul_buku" value="
        <?php
        if (isset($_POST['kode_buku']) && $_POST['kode_buku'] != "") {
            $kode = $_POST['kode_buku'];
            $query_judul = "SELECT * FROM buku WHERE KD_BUKU = '$kode'";
            $hasil = mysqli_query($conn, $query_judul);
            $data = mysqli_fetch_assoc($hasil);
            echo $data['JUDUL'];
        }
        ?>
    " readonly>
</form>
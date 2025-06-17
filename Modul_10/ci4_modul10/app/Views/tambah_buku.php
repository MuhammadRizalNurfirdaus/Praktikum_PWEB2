<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Buku</title>
</head>

<body>
    <h1>Form Tambah Data Buku</h1>

    <?php helper('form'); ?>

    <?= form_open('c_buku/tambahdata') ?>
    <table>
        <tr>
            <td>Kode Buku</td>
            <td>: <input type="text" name="kd_buku" required></td>
        </tr>
        <tr>
            <td>Judul Buku</td>
            <td>: <input type="text" name="judul_buku" size="50" required></td>
        </tr>
        <tr>
            <td>Pengarang</td>
            <td>: <input type="text" name="pengarang_buku" size="50" required></td>
        </tr>
        <tr>
            <td>Penerbit</td>
            <td>: <input type="text" name="penerbit_buku" size="50" required></td>
        </tr>
        <tr>
            <td></td>
            <td>  <input type="submit" value="SIMPAN"> <a href="<?= site_url('c_buku') ?>"><button type="button">Batal</button></a></td>
        </tr>
    </table>
    <?= form_close() ?>
</body>

</html>
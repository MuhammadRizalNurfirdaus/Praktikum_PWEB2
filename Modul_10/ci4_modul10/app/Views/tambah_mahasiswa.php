<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Data Mahasiswa</title>
</head>

<body>
    <h1>Form Tambah Data Mahasiswa</h1>

    <?php helper('form'); ?>
    <?= form_open('c_mahasiswa/tambah') ?>
    <table>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td><input type="text" name="nim" required></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><input type="text" name="nama" required></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><input type="text" name="alamat" required></td>
        </tr>
        <tr>
            <td colspan="3">
                <button type="submit">Simpan Data</button>
            </td>
        </tr>
    </table>
    <?= form_close() ?>
</body>

</html>
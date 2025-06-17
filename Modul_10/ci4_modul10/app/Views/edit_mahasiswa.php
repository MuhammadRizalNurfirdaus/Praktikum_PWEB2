<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
</head>

<body>
    <h1>Form Edit Data Mahasiswa</h1>

    <form action="/mahasiswa/update/<?= $mahasiswa['NIM']; ?>" method="post">
        <?= csrf_field(); ?>
        <table>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td><b><?= $mahasiswa['NIM']; ?></b> (Tidak bisa diubah)</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama" value="<?= $mahasiswa['NAMA']; ?>" required></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><input type="text" name="alamat" value="<?= $mahasiswa['ALAMAT']; ?>" required></td>
            </tr>
            <tr>
                <td colspan="3">
                    <button type="submit">Update Data</button>
                </td>
            </tr>
        </table>
    </form>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa FKOM</title>
</head>

<body>
    <h1>Data Mahasiswa FKOM</h1>

    <?php
    // Menampilkan pesan sukses jika ada (setelah redirect dari proses tambah)
    if (session()->getFlashdata('pesan')) {
        echo '<script>alert("' . session()->getFlashdata('pesan') . '");</script>';
    }
    ?>

    <p>
        <a href="<?= base_url('c_mahasiswa/tambah') ?>">+ Tambah Data Mahasiswa</a>
    </p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $mhs) : ?>
                <tr>
                    <td><?= $mhs['NIM'] ?></td>
                    <td><?= $mhs['NAMA'] ?></td>
                    <td><?= $mhs['ALAMAT'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
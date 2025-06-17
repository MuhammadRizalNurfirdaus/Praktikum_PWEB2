<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
</head>

<body>
    <h1>Data Mahasiswa FKOM</h1>

    <!-- Menampilkan info user yang login -->
    <p>Selamat datang, <b><?= session()->get('username'); ?></b>! | <a href="/logout">Logout</a></p>

    <?php if (session()->getFlashdata('pesan')) : ?>
        <div style="color: green;"><?= session()->getFlashdata('pesan'); ?></div>
    <?php endif; ?>

    <p><a href="/mahasiswa/tambah">+ Tambah Data Mahasiswa</a></p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $mhs) : ?>
                <tr>
                    <td><?= $mhs['NIM']; ?></td>
                    <td><?= $mhs['NAMA']; ?></td>
                    <td><?= $mhs['ALAMAT']; ?></td>
                    <td>
                        <a href="/mahasiswa/edit/<?= $mhs['NIM']; ?>">Edit</a>
                        |
                        <form action="/mahasiswa/delete/<?= $mhs['NIM']; ?>" method="post" style="display:inline;">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
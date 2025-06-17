<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Buku Perpustakaan</title>
</head>

<body>
    <h1>Sistem Informasi Perpustakaan</h1>

    <?php helper('url'); ?>

    <?php
    // Menampilkan pesan notifikasi dari session
    if (session()->getFlashdata('message')) {
        echo '<script>alert("' . session()->getFlashdata('message') . '");</script>';
    }
    ?>

    <p><a href="<?= site_url('c_buku/tambahdata') ?>">+ TAMBAH DATA BUKU</a></p>

    <table width="80%" border="1" cellpadding="5" cellspacing="0">
        <tr style="background-color:#00FF66; text-align:center;">
            <th colspan="5">DATA BUKU</th>
        </tr>
        <tr style="background-color:#CCFF00; text-align:center;">
            <th>KODE</th>
            <th>JUDUL</th>
            <th>PENGARANG</th>
            <th>PENERBIT</th>
            <th>AKSI</th>
        </tr>
        <?php if (!empty($records) && is_array($records)) : ?>
            <?php foreach ($records as $row) : ?>
                <tr>
                    <td><?= esc($row['KD_BUKU']) ?></td>
                    <td><?= esc($row['JUDUL']) ?></td>
                    <td><?= esc($row['PENGARANG']) ?></td>
                    <td><?= esc($row['PENERBIT']) ?></td>
                    <td style="text-align:center;">
                        <a href="<?= site_url('c_buku/updatedata/' . $row['KD_BUKU']) ?>">EDIT</a>
                        |
                        <a href="<?= site_url('c_buku/deletedata/' . $row['KD_BUKU']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data buku ini?')">HAPUS</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada data buku.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Buku</title>
    <style>
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <h2>DAFTAR DATA BUKU</h2>

    <!-- Tempat untuk menampilkan notifikasi -->
    <?php echo $this->session->flashdata('pesan'); ?>

    <a href="<?php echo site_url('c_buku/tambahdata'); ?>">Tambah Data Buku</a>
    <br><br>

    <table width="80%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Buku</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($buku as $row): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['kode_buku']; ?></td>
                <td><?php echo $row['judul_buku']; ?></td>
                <td><?php echo $row['pengarang_buku']; ?></td>
                <td><?php echo $row['penerbit_buku']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <title>Data Buku</title>
</head>

<body>

    <?php
    // Menampilkan pesan sukses jika ada
    if (session()->getFlashdata('message')) {
        echo '<script>alert("' . session()->getFlashdata('message') . '");</script>';
    }
    ?>

    <a href="<?php echo base_url('c_buku/tambahdata'); ?>">TAMBAH DATA BUKU</a>

    <p></p>

    <table width="50%" border="1">
        <th colspan="4" bgcolor="#00FF66">DATA BUKU</th>
        <tr bgcolor="#CCFF00" align="center">
            <td>KODE</td>
            <td>JUDUL</td>
            <td>PENGARANG</td>
            <td>PENERBIT</td>
        </tr>
        <?php foreach ($records as $row) : ?>
            <tr>
                <td><?php echo $row['KD_BUKU']; ?></td>
                <td><?php echo $row['JUDUL']; ?></td>
                <td><?php echo $row['PENGARANG']; ?></td>
                <td><?php echo $row['PENERBIT']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
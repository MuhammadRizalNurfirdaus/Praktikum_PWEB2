<!DOCTYPE html>
<html>

<head>
    <title>Untitled Document</title>
</head>

<body>
    <?php echo anchor('c_buku/formtambah', 'TAMBAH DATA BUKU'); ?>
    <br>
    <table width="50%" border="1">
        <tr>
            <th colspan="6" bgcolor="#00FF66">DATA BUKU</th>
        </tr>
        <tr bgcolor="#CCFF00" align="center">
            <td>KODE</td>
            <td>JUDUL</td>
            <td>PENGARANG</td>
            <td>PENERBIT</td>
            <td colspan="2">AKSI</td>
        </tr>
        <?php foreach ($records as $row) { ?>
            <tr>
                <td><?php echo $row['KD_BUKU']; ?></td>
                <td><?php echo $row['JUDUL']; ?></td>
                <td><?php echo $row['PENGARANG']; ?></td>
                <td><?php echo $row['PENERBIT']; ?></td>
                <td><?php echo anchor('c_buku/updatedata/' . $row['KD_BUKU'], 'EDIT'); ?></td>
                <td><?php echo anchor('c_buku/deletedata/' . $row['KD_BUKU'], 'HAPUS'); ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>
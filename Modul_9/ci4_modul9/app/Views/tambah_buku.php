<!DOCTYPE html>
<html>

<head>
    <title>Tambah Data Buku</title>
</head>

<body>
    <h2>DATA BUKU</h2>

    <?php
    // Muat form helper dengan cara CodeIgniter 4
    helper('form');
    ?>

    <?php echo form_open('c_buku/tambahdata'); ?>

    <table width="20%" border="1">
        <tr>
            <td>Kode Buku</td>
            <td><?php echo form_input('kd_buku'); ?></td>
        </tr>
        <tr>
            <td>Judul Buku</td>
            <td><?php echo form_input('judul_buku'); ?></td>
        </tr>
        <tr>
            <td>Pengarang</td>
            <td><?php echo form_input('pengarang_buku'); ?></td>
        </tr>
        <tr>
            <td>Penerbit</td>
            <td><?php echo form_input('penerbit_buku'); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <?php echo form_submit('submit', 'SIMPAN', 'id="submit"'); ?>
            </td>
        </tr>
    </table>

    <?php echo form_close(); ?>
</body>

</html>
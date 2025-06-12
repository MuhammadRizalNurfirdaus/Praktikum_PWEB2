<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Buku</title>
</head>
<body>
    <h2>TAMBAH DATA BUKU</h2>
    
    <?php echo form_open('c_buku/tambahdata'); ?>
    
    <table width="30%" border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td>Kode Buku</td>
            <td><?php echo form_input('kd_buku', set_value('kd_buku'), 'required'); ?></td> 
        </tr>
        <tr>
            <td>Judul Buku</td>
            <td><?php echo form_input('judul_buku', set_value('judul_buku'), 'required'); ?></td>
        </tr>
        <tr>
            <td>Pengarang</td>
            <td><?php echo form_input('pengarang_buku', set_value('pengarang_buku'), 'required'); ?></td>
        </tr>
        <tr>
            <td>Penerbit</td>
            <td><?php echo form_input('penerbit_buku', set_value('penerbit_buku'), 'required'); ?></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <?php echo form_submit('submit', 'SIMPAN', 'id=submit');?>
            </td>
        </tr>
    </table>
    
    <?php echo form_close(); ?>
    <br>
    <a href="<?php echo site_url('c_buku'); ?>">Kembali ke Daftar Buku</a>
</body>
</html>

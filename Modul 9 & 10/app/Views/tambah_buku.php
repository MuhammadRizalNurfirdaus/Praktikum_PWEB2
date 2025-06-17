<!DOCTYPE html>
<html>
    <head>
        <title>Tambah Buku</title>
    </head>
    <body>
        <?php
            if (!empty($datarecord)) {
                $ket = "UBAH";
                $tombol = "UPDATE";
                $row = (object)$datarecord; 
                echo '<form action="' . base_url('index.php/c_buku/updatedata/' . $row->KD_BUKU) . '" method="post">';
            } else {
                $ket = "TAMBAH";
                $tombol = "SIMPAN";
                $row = (object)[
                    'KD_BUKU' => '',
                    'JUDUL' => '',
                    'PENGARANG' => '',
                    'PENERBIT' => ''
                ];
                echo '<form action="' . base_url('index.php/c_buku/tambahdata') . '" method="post">';
            }
        ?>
        <h2><?php echo 'DATA BUKU'; ?></h2>
        <table border="1">
            <tr>
                <td>Kode Buku</td>
                <td><input type="text" name="kd_buku" value="<?php echo $row->KD_BUKU; ?>"></td>
            </tr>
            <tr>
                <td>Judul Buku</td>
                <td><input type="text" name="judul_buku" value="<?php echo $row->JUDUL; ?>"></td>
            </tr>
            <tr>
                <td>Pengarang</td>
                <td><input type="text" name="pengarang_buku" value="<?php echo $row->PENGARANG; ?>"></td>
            </tr>
            <tr>
                <td>Penerbit</td>
                <td><input type="text" name="penerbit_buku" value="<?php echo $row->PENERBIT; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="<?php echo $tombol; ?>"></td>
            </tr>
        </table>
        </form>
    </body>
</html>

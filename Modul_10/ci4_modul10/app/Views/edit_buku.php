<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ubah Data Buku</title>
</head>

<body>
    <h1>Form Ubah Data Buku</h1>

    <?php helper(['form', 'url']); ?>

    <?= form_open(site_url('c_buku/updatedata/' . $datarecord['KD_BUKU'])) ?>
    <table>
        <tr>
            <td>Kode Buku</td>
            <td>: <b><?= esc($datarecord['KD_BUKU']) ?></b> (Tidak bisa diubah)</td>
        </tr>
        <tr>
            <td>Judul Buku</td>
            <td>: <input type="text" name="judul_buku" value="<?= esc($datarecord['JUDUL']) ?>" size="50" required></td>
        </tr>
        <tr>
            <td>Pengarang</td>
            <td>: <input type="text" name="pengarang_buku" value="<?= esc($datarecord['PENGARANG']) ?>" size="50" required></td>
        </tr>
        <tr>
            <td>Penerbit</td>
            <td>: <input type="text" name="penerbit_buku" value="<?= esc($datarecord['PENERBIT']) ?>" size="50" required></td>
        </tr>
        <tr>
            <td></td>
            <td>  <input type="submit" value="UPDATE"> <a href="<?= site_url('c_buku') ?>"><button type="button">Batal</button></a></td>
        </tr>
    </table>
    <?= form_close() ?>
</body>

</html>
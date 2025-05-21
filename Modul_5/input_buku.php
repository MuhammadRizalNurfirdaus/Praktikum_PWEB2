<?php
// File: input_buku.php
// Lokasi: Pemrograman_Web_2/Praktikum/Modul_5/input_buku.php

$is_edit_mode = (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($data) && $data !== null);

$page_title = $is_edit_mode ? "Edit Data Buku" : "Tambah Data Buku Baru";
$submit_button_text = $is_edit_mode ? "Update Data" : "Simpan Data";

$kode_buku_val  = $is_edit_mode ? htmlspecialchars($data['KD_BUKU']) : '';
$judul_val      = $is_edit_mode ? htmlspecialchars($data['JUDUL']) : '';
$pengarang_val  = $is_edit_mode ? htmlspecialchars($data['PENGARANG']) : '';
$penerbit_val   = $is_edit_mode ? htmlspecialchars($data['PENERBIT']) : '';

?>

<h2><?php echo $page_title; ?></h2>

<form action="index.php?menu=simpan_buku" method="POST">
    <?php if ($is_edit_mode): ?>

        <input type="hidden" name="id_buku_lama" value="<?php echo htmlspecialchars($data['KD_BUKU']); ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="kode_buku">Kode Buku:</label>

        <input type="text" id="kode_buku" name="kode_buku" value="<?php echo $kode_buku_val; ?>" required>

    </div>

    <div class="form-group">
        <label for="judul">Judul Buku:</label>
        <input type="text" id="judul" name="judul" value="<?php echo $judul_val; ?>" required>
    </div>

    <div class="form-group">
        <label for="pengarang">Pengarang:</label>
        <input type="text" id="pengarang" name="pengarang" value="<?php echo $pengarang_val; ?>">
    </div>

    <div class="form-group">
        <label for="penerbit">Penerbit:</label>
        <input type="text" id="penerbit" name="penerbit" value="<?php echo $penerbit_val; ?>">
    </div>

    <div class="form-group">
        <input type="submit" name="submit_action" value="<?php echo $submit_button_text; ?>">
        <a href="index.php?menu=tampil_buku" class="button cancel">Batal</a>
    </div>
</form>
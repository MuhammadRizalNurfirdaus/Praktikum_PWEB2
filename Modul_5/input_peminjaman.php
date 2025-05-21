<?php
// File: input_peminjaman.php
// Variabel $data_peminjaman dan $conn dari index.php

$is_edit_mode_pinjam = (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($data_peminjaman) && $data_peminjaman !== null);

$page_title_pinjam = $is_edit_mode_pinjam ? "Edit Data Peminjaman" : "Tambah Data Peminjaman Baru";
$submit_text_pinjam = $is_edit_mode_pinjam ? "Update Data Peminjaman" : "Simpan Data Peminjaman";

// Nilai default untuk form
$kode_pinjam_val     = $is_edit_mode_pinjam ? htmlspecialchars($data_peminjaman['KODE_PINJAM']) : '';
$tgl_pinjam_val      = $is_edit_mode_pinjam ? htmlspecialchars($data_peminjaman['TANGGAL_PINJAM']) : date('Y-m-d'); // Default tanggal hari ini untuk tambah baru
$nim_pinjam_val      = $is_edit_mode_pinjam ? htmlspecialchars($data_peminjaman['NIM']) : '';
$kode_buku_pinjam_val = $is_edit_mode_pinjam ? htmlspecialchars($data_peminjaman['KODE_BUKU']) : '';
$tgl_kembali_val     = $is_edit_mode_pinjam && !empty($data_peminjaman['TANGGAL_KEMBALI']) ? htmlspecialchars($data_peminjaman['TANGGAL_KEMBALI']) : '';

?>
<h2><?php echo $page_title_pinjam; ?> (Database: PERPUSTAKAAN_FKOM)</h2>

<?php if ($is_edit_mode_pinjam && $data_peminjaman === null): ?>
    <p class="message error">Data peminjaman yang akan diedit tidak ditemukan. Silakan kembali ke <a href="index.php?menu=tampil_peminjaman">daftar peminjaman</a>.</p>
<?php else: ?>
    <form action="index.php?menu=simpan_peminjaman" method="POST">
        <?php if ($is_edit_mode_pinjam): ?>
            <input type="hidden" name="kode_pinjam_lama" value="<?php echo htmlspecialchars($data_peminjaman['KODE_PINJAM']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="kode_pinjam_input">Kode Pinjam:</label>
            <input type="text" id="kode_pinjam_input" name="kode_pinjam" value="<?php echo $kode_pinjam_val; ?>"
                <?php if ($is_edit_mode_pinjam) echo 'readonly'; // Kode Pinjam sebagai PK tidak diubah saat edit 
                ?> required>
            <?php if ($is_edit_mode_pinjam): ?>
                <small>Kode Pinjam tidak dapat diubah.</small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="tgl_pinjam_input">Tanggal Pinjam:</label>
            <input type="date" id="tgl_pinjam_input" name="tanggal_pinjam" value="<?php echo $tgl_pinjam_val; ?>" required>
        </div>

        <div class="form-group">
            <label for="nim_pinjam_input">NIM Peminjam:</label>
            <input type="text" id="nim_pinjam_input" name="nim" value="<?php echo $nim_pinjam_val; ?>" required>
            <!-- Idealnya: <select name="nim"> ... opsi dari tabel mahasiswa ... </select> -->
        </div>

        <div class="form-group">
            <label for="kode_buku_pinjam_input">Kode Buku:</label>
            <input type="text" id="kode_buku_pinjam_input" name="kode_buku" value="<?php echo $kode_buku_pinjam_val; ?>" required>
            <!-- Idealnya: <select name="kode_buku"> ... opsi dari tabel buku ... </select> -->
        </div>

        <div class="form-group">
            <label for="tgl_kembali_input">Tanggal Kembali (Kosongkan jika belum dikembalikan):</label>
            <input type="date" id="tgl_kembali_input" name="tanggal_kembali" value="<?php echo $tgl_kembali_val; ?>">
        </div>

        <div class="form-group">
            <input type="submit" name="submit_peminjaman" value="<?php echo $submit_text_pinjam; ?>">
            <a href="index.php?menu=tampil_peminjaman" class="button cancel">Batal</a>
        </div>
    </form>
<?php endif; ?>
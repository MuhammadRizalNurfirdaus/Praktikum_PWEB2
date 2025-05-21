<?php
// File: input_mahasiswa.php
// Variabel $data_mahasiswa (untuk mode edit) dan $conn (koneksi)
// sudah disiapkan oleh tampil_data_mahasiswa.php (yang di-require oleh index.php)
// dan $conn disediakan oleh koneksi_fkom.php yang di-require oleh tampil_data_mahasiswa.php.

// Cek apakah ini mode edit berdasarkan parameter aksi dan ketersediaan $data_mahasiswa
$is_edit_mode_mhs = (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($data_mahasiswa) && $data_mahasiswa !== null);

$page_title_mhs = $is_edit_mode_mhs ? "Edit Data Mahasiswa" : "Tambah Data Mahasiswa Baru";
$submit_text_mhs = $is_edit_mode_mhs ? "Update Data Mahasiswa" : "Simpan Data Mahasiswa";

// Nilai default untuk form (akan diisi jika mode edit)
$nim_val       = $is_edit_mode_mhs ? htmlspecialchars($data_mahasiswa['NIM']) : '';
$nama_val      = $is_edit_mode_mhs ? htmlspecialchars($data_mahasiswa['NAMA']) : '';
$fakultas_val  = $is_edit_mode_mhs ? htmlspecialchars($data_mahasiswa['FAKULTAS']) : '';
$jurusan_val   = $is_edit_mode_mhs ? htmlspecialchars($data_mahasiswa['JURUSAN']) : '';
$jenjang_val   = $is_edit_mode_mhs ? htmlspecialchars($data_mahasiswa['JENJANG']) : '';

// Jika mode edit tapi $data_mahasiswa null (misal NIM tidak ketemu), beri pesan
if ($is_edit_mode_mhs && $data_mahasiswa === null) {
    // Pesan error sudah di-set di tampil_data_mahasiswa.php, tampilkan di sini atau redirect.
    // Untuk simpel, kita bisa cegah form tampil jika data tidak ada.
    // Atau biarkan tampil dengan field kosong dan pesan error dari session.
}

?>
<h2><?php echo $page_title_mhs; ?> (Database: PERPUSTAKAAN_FKOM)</h2>

<?php if ($is_edit_mode_mhs && $data_mahasiswa === null): ?>
    <p class="message error">Data mahasiswa yang akan diedit tidak ditemukan. Silakan kembali ke <a href="index.php?menu=tampil_mahasiswa">daftar mahasiswa</a>.</p>
<?php else: ?>
    <form action="index.php?menu=simpan_mahasiswa" method="POST">
        <?php if ($is_edit_mode_mhs): ?>
            <!-- Hidden field untuk menyimpan NIM lama, digunakan di WHERE clause saat update -->
            <input type="hidden" name="nim_lama" value="<?php echo htmlspecialchars($data_mahasiswa['NIM']); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nim_mhs">NIM:</label>
            <input type="text" id="nim_mhs" name="nim" value="<?php echo $nim_val; ?>"
                <?php if ($is_edit_mode_mhs) echo 'readonly'; // NIM sebagai Primary Key biasanya tidak diubah saat edit 
                ?> required>
            <?php if ($is_edit_mode_mhs): ?>
                <small>NIM tidak dapat diubah setelah data dibuat.</small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="nama_mhs">Nama Mahasiswa:</label>
            <input type="text" id="nama_mhs" name="nama" value="<?php echo $nama_val; ?>" required>
        </div>
        <div class="form-group">
            <label for="fakultas_mhs">Fakultas:</label>
            <input type="text" id="fakultas_mhs" name="fakultas" value="<?php echo $fakultas_val; ?>">
        </div>
        <div class="form-group">
            <label for="jurusan_mhs">Jurusan:</label>
            <input type="text" id="jurusan_mhs" name="jurusan" value="<?php echo $jurusan_val; ?>">
        </div>
        <div class="form-group">
            <label for="jenjang_mhs">Jenjang (misal: S1, D3):</label>
            <input type="text" id="jenjang_mhs" name="jenjang" value="<?php echo $jenjang_val; ?>">
        </div>
        <div class="form-group">
            <input type="submit" name="submit_mahasiswa" value="<?php echo $submit_text_mhs; ?>">
            <a href="index.php?menu=tampil_mahasiswa" class="button cancel">Batal</a>
        </div>
    </form>
<?php endif; ?>
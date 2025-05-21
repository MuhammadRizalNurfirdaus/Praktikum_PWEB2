<?php
// File: tampil_mahasiswa.php
// Variabel $r_mahasiswa (hasil query daftar) dan $conn (koneksi)
// sudah disiapkan oleh tampil_data_mahasiswa.php (yang di-require oleh index.php)
// dan $conn disediakan oleh koneksi_fkom.php yang di-require oleh tampil_data_mahasiswa.php.
?>
<h2>DAFTAR DATA MAHASISWA (Database: PERPUSTAKAAN_FKOM)</h2>
<div class="center-link" style="margin-bottom: 20px; text-align: left;">
    <a href="index.php?menu=input_mahasiswa" class="button">Tambah Data Mahasiswa</a>
</div>
<table>
    <thead>
        <tr>
            <th>NIM</th>
            <th>NAMA</th>
            <th>FAKULTAS</th>
            <th>JURUSAN</th>
            <th>JENJANG</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Periksa apakah $r_mahasiswa (hasil query) ada, tidak null, dan memiliki baris data
        if (isset($r_mahasiswa) && $r_mahasiswa !== null && mysqli_num_rows($r_mahasiswa) > 0) {
            while ($row_mhs = mysqli_fetch_assoc($r_mahasiswa)) {
        ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_mhs['NIM']); ?></td>
                    <td><?php echo htmlspecialchars($row_mhs['NAMA']); ?></td>
                    <td><?php echo htmlspecialchars($row_mhs['FAKULTAS']); ?></td>
                    <td><?php echo htmlspecialchars($row_mhs['JURUSAN']); ?></td>
                    <td><?php echo htmlspecialchars($row_mhs['JENJANG']); ?></td>
                    <td class="action-links">
                        <a class="edit" href="index.php?menu=input_mahasiswa&aksi=edit&nim=<?php echo urlencode($row_mhs['NIM']); ?>">Edit</a>
                        <a class="delete" href="index.php?menu=hapus_mahasiswa&aksi=delete&nim=<?php echo urlencode($row_mhs['NIM']); ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa <?php echo htmlspecialchars(addslashes($row_mhs['NAMA'])); ?> (NIM: <?php echo htmlspecialchars(addslashes($row_mhs['NIM'])); ?>)?');">Hapus</a>
                    </td>
                </tr>
        <?php
            } // Akhir while loop
            mysqli_free_result($r_mahasiswa); // Bebaskan memori hasil query
        } else {
            // Jika tidak ada data atau query gagal
            $colspan_mhs = 6; // Jumlah kolom di tabel mahasiswa
            echo "<tr><td colspan=\"$colspan_mhs\" style=\"text-align:center;\">";
            // Cek apakah ada error spesifik dari koneksi atau query
            if (isset($conn) && mysqli_error($conn) && (!isset($r_mahasiswa) || $r_mahasiswa === null || $r_mahasiswa === false)) {
                echo "Terjadi kesalahan saat mengambil data mahasiswa. Silakan coba lagi.";
                // Untuk debug: echo "<br><small>Error: " . htmlspecialchars(mysqli_error($conn)) . "</small>";
            } else {
                echo "Tidak ada data mahasiswa yang tersimpan di database PERPUSTAKAAN_FKOM.";
            }
            echo "</td></tr>";
        }
        ?>
    </tbody>
</table>
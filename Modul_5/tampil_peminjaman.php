<?php
// File: tampil_peminjaman.php
// Variabel $r_peminjaman dan $conn dari index.php
?>
<h2>DAFTAR DATA PEMINJAMAN (Database: PERPUSTAKAAN_FKOM)</h2>
<div class="center-link" style="margin-bottom: 20px; text-align: left;">
    <a href="index.php?menu=input_peminjaman" class="button">Tambah Data Peminjaman</a>
</div>
<table>
    <thead>
        <tr>
            <th>KODE PINJAM</th>
            <th>TGL PINJAM</th>
            <th>NIM</th>
            <th>NAMA MHS</th>
            <th>KODE BUKU</th>
            <th>JUDUL BUKU</th>
            <th>TGL KEMBALI</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($r_peminjaman) && $r_peminjaman !== null && mysqli_num_rows($r_peminjaman) > 0) {
            while ($row_pinjam = mysqli_fetch_assoc($r_peminjaman)) {
        ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_pinjam['KODE_PINJAM']); ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['TANGGAL_PINJAM']); ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['NIM']); ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['NAMA_MAHASISWA'] ?? 'N/A'); // Handle jika NIM tidak ada di tabel mahasiswa 
                        ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['KODE_BUKU']); ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['JUDUL_BUKU'] ?? 'N/A'); // Handle jika KODE_BUKU tidak ada di tabel buku (tergantung JOIN) 
                        ?></td>
                    <td><?php echo htmlspecialchars($row_pinjam['TANGGAL_KEMBALI'] ?? 'Belum Dikembalikan'); ?></td>
                    <td class="action-links">
                        <a class="edit" href="index.php?menu=input_peminjaman&aksi=edit&kode_pinjam=<?php echo urlencode($row_pinjam['KODE_PINJAM']); ?>">Edit</a>
                        <a class="delete" href="index.php?menu=hapus_peminjaman&aksi=delete&kode_pinjam=<?php echo urlencode($row_pinjam['KODE_PINJAM']); ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus data peminjaman dengan Kode Pinjam <?php echo htmlspecialchars(addslashes($row_pinjam['KODE_PINJAM'])); ?>?');">Hapus</a>
                    </td>
                </tr>
        <?php
            }
            mysqli_free_result($r_peminjaman);
        } else {
            $colspan_pinjam = 8;
            echo "<tr><td colspan=\"$colspan_pinjam\" style=\"text-align:center;\">";
            if (isset($conn) && mysqli_error($conn) && (!isset($r_peminjaman) || $r_peminjaman === null || $r_peminjaman === false)) {
                echo "Terjadi kesalahan saat mengambil data peminjaman.";
            } else {
                echo "Tidak ada data peminjaman yang tersimpan.";
            }
            echo "</td></tr>";
        }
        ?>
    </tbody>
</table>
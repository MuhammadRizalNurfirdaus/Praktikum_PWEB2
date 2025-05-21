<?php
// File: tampil_buku.php
// Lokasi: Pemrograman_Web_2/Praktikum/Modul_5/tampil_buku.php
// File ini menampilkan tabel data buku.
// Variabel $r (hasil query semua buku) dan $conn sudah tersedia.
?>

<h2>DAFTAR DATA BUKU</h2>

<table width="100%">
    <thead>
        <tr>
            <th>KODE</th>
            <th>JUDUL</th>
            <th>PENGARANG</th>
            <th>PENERBIT</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Periksa apakah $r (hasil query) ada, tidak null, dan memiliki baris data
        if (isset($r) && $r !== null && mysqli_num_rows($r) > 0) {
            while ($row_data = mysqli_fetch_assoc($r)) {
        ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_data['KD_BUKU']); ?></td>
                    <td><?php echo htmlspecialchars($row_data['JUDUL']); ?></td>
                    <td><?php echo htmlspecialchars($row_data['PENGARANG']); ?></td>
                    <td><?php echo htmlspecialchars($row_data['PENERBIT']); ?></td>
                    <td class="action-links">
                        <a class="edit" href="index.php?menu=input_buku&aksi=edit&id=<?php echo urlencode($row_data['KD_BUKU']); ?>">Edit</a>
                        <a class="delete" href="index.php?menu=hapus_buku&aksi=delete&id=<?php echo urlencode($row_data['KD_BUKU']); ?>"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini: <?php echo htmlspecialchars(addslashes($row_data['JUDUL'])); ?>?');">Hapus</a>
                    </td>
                </tr>
        <?php
            } // Akhir while loop
            mysqli_free_result($r); // Bebaskan memori hasil query setelah selesai digunakan
        } else {
            // Jika tidak ada data atau query gagal
            $colspan = 5; // Jumlah kolom di tabel
            echo "<tr><td colspan=\"$colspan\" style=\"text-align:center;\">";
            if (isset($conn) && mysqli_error($conn) && (!isset($r) || $r === null)) {
                // Jika ada error dari koneksi/query DAN $r tidak berhasil di-set
                echo "Terjadi kesalahan saat mengambil data buku. Silakan coba lagi.";
                // echo "<br><small>Error: " . htmlspecialchars(mysqli_error($conn)) . "</small>"; // Untuk debug
            } else {
                echo "Tidak ada data buku yang tersimpan.";
            }
            echo "</td></tr>";
        }
        ?>
    </tbody>
</table>
<div class="center-link">
    <a href="index.php?menu=input_buku" class="button">Tambah Data Buku Baru</a>
</div>
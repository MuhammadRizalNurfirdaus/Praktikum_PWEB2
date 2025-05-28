<?php
// LapPeminjaman.php

require_once "Koneksi_FKOM.php";

// Query untuk mengambil data peminjaman dengan JOIN ke tabel buku dan mahasiswa
$query_peminjaman = "
    SELECT 
        p.KODE_PINJAM, 
        p.TANGGAL_PINJAM, 
        p.TANGGAL_KEMBALI,
        m.NIM,
        m.NAMA AS NAMA_MAHASISWA,
        b.KODE AS KD_BUKU,  -- PERBAIKAN DI SINI
        b.JUDUL AS JUDUL_BUKU
    FROM 
        peminjaman p
    JOIN 
        mahasiswa m ON p.NIM = m.NIM
    JOIN 
        buku b ON p.KODE_BUKU = b.KODE -- DAN PERBAIKAN DI SINI
    ORDER BY 
        p.TANGGAL_PINJAM DESC, p.KODE_PINJAM ASC
";

$hasil_peminjaman = mysqli_query($conn_fkom, $query_peminjaman);

if (!$hasil_peminjaman) {
    echo "Error query peminjaman: " . htmlspecialchars(mysqli_error($conn_fkom));
    exit;
}
?>
<html>

<head>
    <title>Laporan Data Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }

        th {
            background-color: #a0e0a0;
            text-align: center;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #777;
        }
    </style>
</head>

<body>
    <h2>LAPORAN DATA PEMINJAMAN</h2>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE PINJAM</th>
                <th>TGL PINJAM</th>
                <th>NIM</th>
                <th>NAMA MAHASISWA</th>
                <th>KODE BUKU</th>
                <th>JUDUL BUKU</th>
                <th>TGL KEMBALI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($hasil_peminjaman) > 0) {
                $no = 1;
                while ($data = mysqli_fetch_assoc($hasil_peminjaman)) {
                    echo "<tr>";
                    echo "<td style='text-align: center;'>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($data['KODE_PINJAM']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['TANGGAL_PINJAM']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['NIM']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['NAMA_MAHASISWA']) . "</td>";
                    echo "<td>" . htmlspecialchars($data['KD_BUKU']) . "</td>"; // Ini akan menggunakan alias KD_BUKU
                    echo "<td>" . htmlspecialchars($data['JUDUL_BUKU']) . "</td>";
                    echo "<td>" . (isset($data['TANGGAL_KEMBALI']) && $data['TANGGAL_KEMBALI'] ? htmlspecialchars($data['TANGGAL_KEMBALI']) : '-') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='no-data'>Tidak ada data peminjaman.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
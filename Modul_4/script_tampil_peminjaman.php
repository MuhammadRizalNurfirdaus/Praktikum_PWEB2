<!DOCTYPE html>
<html>

<head>
    <title>Data Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 95%;
            margin: 20px auto;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Data Peminjaman Buku Mahasiswa</h2>

    <table>
        <tr>
            <th>Kode Pinjam</th>
            <th>Nama Mahasiswa</th>
            <th>Fakultas</th>
            <th>Judul Buku</th>
            <th>Pengarang</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
        </tr>

        <?php
        // Koneksi ke database
        $koneksi = mysqli_connect("localhost", "root", "", "perpustakaan_fkom");

        // Cek koneksi
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
            exit;
        }

        // Query gabungan dari 3 tabel
        $query = "
        SELECT 
            p.KODE_PINJAM,
            m.NAMA,
            m.FAKULTAS,
            b.JUDUL,
            b.PENGARANG,
            p.TGL_PINJAM,
            p.TGL_KEMBALI
        FROM peminjaman p
        JOIN mahasiswa m ON p.NIM = m.NIM
        JOIN buku b ON p.KD_BUKU = b.KD_BUKU
        ORDER BY p.KODE_PINJAM ASC
    ";

        $result = mysqli_query($koneksi, $query);

        // Menampilkan data
        while ($data = mysqli_fetch_assoc($result)) {
            echo "<tr>
            <td>{$data['KODE_PINJAM']}</td>
            <td>{$data['NAMA']}</td>
            <td>{$data['FAKULTAS']}</td>
            <td>{$data['JUDUL']}</td>
            <td>{$data['PENGARANG']}</td>
            <td>{$data['TGL_PINJAM']}</td>
            <td>{$data['TGL_KEMBALI']}</td>
        </tr>";
        }

        mysqli_close($koneksi);
        ?>

    </table>

</body>

</html>
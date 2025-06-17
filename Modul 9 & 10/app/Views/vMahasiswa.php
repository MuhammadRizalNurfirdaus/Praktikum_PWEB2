<!DOCTYPE html>
<html>
<head>
    <title>DATA MAHASISWA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffaf5;
            color: #333;
            margin: 40px;
        }

        h2 {
            color: #ff6600;
        }

        a {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #ff6600;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }

        a:hover {
            background-color: #e65c00;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ffb366;
        }

        th {
            background-color: #ff8000;
            color: white;
        }

        td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ffb366;
        white-space: nowrap;
    }

    td a {
        display: inline-block;
        padding: 6px 10px;
        background-color: #ff6600;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        margin: 2px;
    }

    td a:hover {
        background-color: #e65c00;
    }
    </style>
</head>
<body>
    <h2>Data Mahasiswa</h2>
    <a href="<?= base_url('cMahasiswa/formtambah') ?>">Tambah Data Mahasiswa</a>
    <table>
        <tr>
            <th>NIM</th>
            <th>Nama</th>
            <th>Fakultas</th>
            <th>Jurusan</th>
            <th>Jenjang</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($records as $row): ?>
            <tr>
                <td><?= $row['NIM']; ?></td>
                <td><?= $row['NAMA']; ?></td>
                <td><?= $row['FAKULTAS']; ?></td>
                <td><?= $row['JURUSAN']; ?></td>
                <td><?= $row['JENJANG']; ?></td>
                <td>
                    <a href="<?= base_url('cMahasiswa/updatedata/' . $row['NIM']) ?>">Edit</a> |
                    <a href="<?= base_url('cMahasiswa/deletedata/' . $row['NIM']) ?>" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

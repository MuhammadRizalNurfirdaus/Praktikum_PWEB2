<!DOCTYPE html>
<html>
<head>
    <title>Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
            padding: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .selected {
            background-color: #d0ebff !important;
        }
        a.button {
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .aksi {
            margin-top: 20px;
            display: none;
        }
        .aksi a {
            margin-right: 10px;
        }
        .user-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Data Mahasiswa</h2>
    <div class="user-info">
        <p>Selamat datang, <?= session()->get('username') ?> |
            <a href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </p>
    </div>
    <a class="button" href="<?= base_url('/mahasiswa/tambah') ?>">Tambah Mahasiswa</a>
    <table id="tabelMahasiswa">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mahasiswa as $row): ?>
                <tr data-nim="<?= esc($row['nim']) ?>">
                    <td><?= esc($row['nim']) ?></td>
                    <td><?= esc($row['nama']) ?></td>
                    <td><?= esc($row['alamat']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="aksi" id="aksi">
        <p>Data terpilih: <strong><span id="nimDipilih"></span></strong></p>
        <a id="editLink" href="#">✏️ Edit</a>
        <a id="hapusLink" href="#" onclick="return confirm('Yakin ingin menghapus data ini?')">🗑 Hapus</a>
    </div>
    <script>
        const rows = document.querySelectorAll("#tabelMahasiswa tbody tr");
        const aksiDiv = document.getElementById("aksi");
        const nimSpan = document.getElementById("nimDipilih");
        const editLink = document.getElementById("editLink");
        const hapusLink = document.getElementById("hapusLink");
        rows.forEach(row => {
            row.addEventListener("click", () => {
                rows.forEach(r => r.classList.remove("selected"));
                row.classList.add("selected");
                const nim = row.getAttribute("data-nim");
                nimSpan.textContent = nim;
                editLink.href = "<?= base_url('/mahasiswa/edit/') ?>" + nim;
                hapusLink.href = "<?= base_url('/mahasiswa/delete/') ?>" + nim;
                aksiDiv.style.display = "block";
            });
        });
    </script>
</body>
</html>
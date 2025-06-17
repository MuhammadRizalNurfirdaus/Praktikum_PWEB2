<!DOCTYPE html>
<html>
<head>
    <title>Edit Mahasiswa</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 40px; }
        form {
            background: #fff; padding: 20px; width: 400px;
            border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea {
            width: 100%; padding: 8px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 6px;
        }
        button {
            margin-top: 15px; padding: 10px 15px; background-color: #ffc107;
            color: white; border: none; border-radius: 6px; cursor: pointer;
        }
        a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Edit Data Mahasiswa</h2>
    <form action="<?= base_url('/mahasiswa/update') ?>" method="post">
        <label>NIM</label>
        <input type="text" name="nim" value="<?= esc($mahasiswa['nim']) ?>" readonly>
        <label>Nama</label>
        <input type="text" name="nama" value="<?= esc($mahasiswa['nama']) ?>" required>
        <label>Alamat</label>
        <textarea name="alamat" required><?= esc($mahasiswa['alamat']) ?></textarea>
        <button type="submit">UPDATE</button>
    </form>
    <a href="<?= base_url('/mahasiswa') ?>">← Kembali ke Daftar Mahasiswa</a>
</body>
</html>
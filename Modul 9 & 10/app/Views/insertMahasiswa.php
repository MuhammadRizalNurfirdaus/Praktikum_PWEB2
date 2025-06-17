<!DOCTYPE html>
<html>
<head>
    <title>Form Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffaf0;
            color: #333;
            margin: 40px;
        }

        h2 {
            color: #ff6600;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border: 2px solid #ffd9b3;
            border-radius: 10px;
            width: 400px;
        }

        table {
            width: 100%;
        }

        td {
            padding: 8px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ffb366;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #e65c00;
        }
    </style>
</head>
<body>
    <h2><?= isset($datarecord) ? 'Form Edit Mahasiswa' : 'Form Tambah Mahasiswa' ?></h2>
    <?php
        if (!empty($datarecord)) {
            $row = (object)$datarecord;
            echo '<form action="' . base_url('cMahasiswa/updatedata/' . $row->NIM) . '" method="post">';
        } else {
            $row = (object)[
                'NIM' => '',
                'NAMA' => '',
                'FAKULTAS' => '',
                'JURUSAN' => '',
                'JENJANG' => ''
            ];
            echo '<form action="' . base_url('cMahasiswa/tambahdata') . '" method="post">';
        }
    ?>
    <table>
        <tr>
            <td>NIM</td>
            <td><input type="text" name="nim" value="<?= $row->NIM ?>" <?= isset($datarecord) ? 'readonly' : '' ?>></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><input type="text" name="nama" value="<?= $row->NAMA ?>"></td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td><input type="text" name="fakultas" value="<?= $row->FAKULTAS ?>"></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><input type="text" name="jurusan" value="<?= $row->JURUSAN ?>"></td>
        </tr>
        <tr>
            <td>Jenjang</td>
            <td><input type="text" name="jenjang" value="<?= $row->JENJANG ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Simpan"></td>
        </tr>
    </table>
    </form>
</body>
</html>

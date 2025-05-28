<html>

<head>
    <title>Laporan Data Buku</title>
</head>

<body>
    <h2>LAPORAN DATA BUKU</h2>
    <table widt="90%" border="1">
        <tr bgcolor="#00ff00" align="center" valign="middle">
            <td>NO</td>
            <td>KODE</td>
            <td>JUDUL</td>
            <td>PENGARANG</td>
            <td>PENERBIT</td>
        </tr>
        <?php
        include "Koneksi.php";
        $q = "SELECT * FROM buku";
        $r = mysqli_query($conn, $q) or die(mysqli_error($conn));
        $no = 1;
        while ($data = mysqli_fetch_array($r)) {
            echo "<tr>
            <td align='center'>$no</td>
            <td>$data[KD_BUKU]</td>
            <td>$data[JUDUL]</td>
            <td>$data[PENGARANG]</td>
            <td>$data[PENERBIT]</td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</body>

</html>
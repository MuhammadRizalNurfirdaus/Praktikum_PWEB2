<html>

<head>
    <title>Data Buku</title>
</head>

<body>
    <form name="form1" method="post" action="">
        <h2>DATA BUKU</h2>
        <table width="50%" borde="1">
            <tr bgcolor="#00ff00" align="center">
                <th>Kode Buku</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
            </tr>
            <?php
            include "tampil_data_buku.php";
            while ($row = mysqli_fetch_array($r)) {
                echo "<tr>";
                echo "<td>{$row['KD_BUKU']}</td>";
                echo "<td>{$row['JUDUL']}</td>";
                echo "<td>{$row['PENGARANG']}</td>";
                echo "<td>{$row['PENERBIT']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </form>
</body>

</html>
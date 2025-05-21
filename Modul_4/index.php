<html>

<head>
    <tittle>PERPUSTAKAAN FKOM UNIKU</tittle>
</head>

<body>
    <form nam="form1" method="post" action="index.php?menu=simpan_buku">
        <PRE>
    <h2>PERPUSTAKAAN ONLINE</h2>
    <?php include "menu.php"; ?>

    <hr color="green" size="4" />
    <?php
    extract($_GET);
    if (isset($menu)) {
        if ($menu == "input_buku") {
            include "input_buku.php";
        } elseif ($menu == "tampil_buku") {
            include "tampil_buku.php";
        } elseif ($menu == "simpan_buku") {
            include "simpan_buku.php";
        }
    }
    ?>

</body>
</html>
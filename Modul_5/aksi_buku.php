<?php
include "koneksi.php";
extract($_GET);
if (isset($aksi)) {
    switch ($aksi) {
        case "edit":
            include "input_buku.php";
            return;
        case "delete":
            $q = "DELETE FROM buku WHERE KD_BUKU = '$id'";
            $r = mysqli_query($koneksi, $q) or die(mysqli_error($koneksi));
            if ($r) {
                echo "Data Berhasil Dihapus";
            }
            break;
    }
}

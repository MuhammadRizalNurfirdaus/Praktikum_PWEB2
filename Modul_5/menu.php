<?php
// Diasumsikan ini adalah bagian dari index.php atau file menu yang di-include
// include_once "koneksi.php"; // Koneksi di awal

if (isset($_GET['menu'])) {
    $menu = $_GET['menu']; // Ambil nilai menu

    if ($menu == "input_buku") {
        include "input_buku.php";
    } elseif ($menu == "tampil_buku") {
        include "tampil_buku.php";
    } elseif ($menu == "simpan_buku") { // Untuk memproses form dari input_buku.php
        include "simpan_buku.php";
    } elseif ($menu == "edit_buku") { // Ditambahkan sesuai instruksi
        // Biasanya, edit_buku akan mengarah ke form input dengan data terisi
        // tampil_data_buku.php akan dipanggil di dalam input_buku.php untuk mengambil data
        include "input_buku.php";
    } elseif ($menu == "hapus_buku") { // Ditambahkan sesuai instruksi
        // Ini bisa langsung mengarah ke skrip yang menangani delete,
        // atau ke tampil_buku.php lagi setelah delete.
        // Jika aksi delete ada di simpan_buku.php atau aksi_buku.php,
        // maka link hapus di tampil_buku.php akan mengirim parameter aksi=delete.
        // Untuk saat ini, modul mengarahkan untuk include tampil_buku.php
        include "tampil_buku.php"; // Sesuai instruksi modul.
        // Logika delete sebenarnya ada di link hapus di tampil_buku.php
        // yang akan memanggil aksi=delete.
        // Anda perlu menambahkan penanganan aksi=delete di aksi_buku.php atau simpan_buku.php
    } else {
        // Halaman default jika menu tidak dikenal, misal tampilkan buku
        include "tampil_buku.php";
    }
} else {
    // Halaman default jika tidak ada parameter menu
    include "tampil_buku.php";
}

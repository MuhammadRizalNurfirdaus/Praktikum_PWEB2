<?php
session_start();
require_once 'koneksi.php'; // Pastikan $conn tersedia

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']); // Password dari form

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = "Username dan Password tidak boleh kosong.";
            header("Location: login.php");
            exit;
        }

        // Query untuk mengambil data admin berdasarkan username
        // SANGAT PENTING: INI MENGGUNAKAN PLAIN TEXT PASSWORD SESUAI TUGAS, TAPI TIDAK AMAN!
        $stmt = mysqli_prepare($conn, "SELECT ID, USERNAME, PASSWORD, NAMA, LEVEL FROM admin WHERE USERNAME = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($admin = mysqli_fetch_assoc($result)) {
            // Membandingkan password dari form dengan password di database (plain text)
            // Jika Anda menggunakan password_hash(), bandingkan dengan password_verify()
            if ($password === $admin['PASSWORD']) { // Perbandingan plain text
                // Login berhasil
                $_SESSION['is_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['ID'];
                $_SESSION['admin_username'] = $admin['USERNAME'];
                $_SESSION['admin_nama'] = $admin['NAMA'];
                $_SESSION['admin_level'] = $admin['LEVEL'];

                // Redirect ke halaman index.php di dalam folder perpustakaan
                // Asumsi folder proyek adalah nim_anda/perpustakaan
                // Jika XAMPP htdocs adalah root, maka /nim_anda/perpustakaan/index.php
                // Jika file ini ada di nim_anda/perpustakaan/, cukup "index.php"
                header("Location: index.php");
                exit;
            } else {
                // Password salah
                $_SESSION['login_error'] = "Username atau Password salah.";
                header("Location: login.php");
                exit;
            }
        } else {
            // Username tidak ditemukan
            $_SESSION['login_error'] = "Username atau Password salah.";
            header("Location: login.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['login_error'] = "Masukkan Username dan Password.";
        header("Location: login.php");
        exit;
    }
} else {
    // Bukan metode POST, redirect ke login
    header("Location: login.php");
    exit;
}
mysqli_close($conn);

<?php
// list_foto.php
require_once "koneksi.php"; // Pastikan ini menggunakan koneksi.php mysqli dan variabel $conn_upload
session_start(); // Untuk pesan notifikasi setelah delete

echo "<!DOCTYPE html><html><head><title>Daftar Foto</title>
      <style> 
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        ul { list-style-type: none; padding: 0; display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; } 
        li { 
            width: 220px; 
            margin-bottom: 20px; 
            border: 1px solid #eee; 
            padding: 10px; 
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        } 
        li img { 
            max-width: 200px; 
            max-height: 150px; 
            height: 150px; /* Tinggi tetap */
            object-fit: cover; /* Agar gambar tidak gepeng */
            margin-bottom: 10px; 
            border: 1px solid #ddd; 
        }
        .file-details { font-size: 0.9em; text-align: center; width: 100%;}
        .file-details strong { display: block; margin-bottom: 5px; word-wrap: break-word; }
        .file-actions a { margin: 5px; text-decoration: none; color: #007bff; padding: 5px 8px; border: 1px solid #007bff; border-radius: 3px;}
        .file-actions a:hover { background-color: #007bff; color: white; }
        .file-actions a.delete { border-color: #dc3545; color: #dc3545; }
        .file-actions a.delete:hover { background-color: #dc3545; color: white; }
        .message { padding: 10px; margin-bottom: 15px; border: 1px solid transparent; border-radius: 4px; text-align:center; }
        .success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .error { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .warning { color: #856404; background-color: #fff3cd; border-color: #ffeeba; }
      </style>
      </head><body>";

// Tampilkan pesan notifikasi
if (isset($_SESSION['delete_message'])) {
    echo '<div class="message ' . ($_SESSION['delete_type'] ?? 'info') . '">' . htmlspecialchars($_SESSION['delete_message']) . '</div>';
    unset($_SESSION['delete_message']);
    unset($_SESSION['delete_type']);
}

echo "<h2>Daftar Foto</h2>";

$query = "SELECT id, name, size, type FROM upload WHERE type LIKE 'image/%' ORDER BY id DESC"; // Hanya tampilkan gambar, urut terbaru
$result = mysqli_query($conn_upload, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<ul>";
    while ($row = mysqli_fetch_assoc($result)) {
        $fileId = htmlspecialchars($row['id']);
        $fileName = htmlspecialchars($row['name']);
        $fileSize = htmlspecialchars($row['size']);
        $fileType = htmlspecialchars($row['type']);
        $filePath = "data/" . $fileName;

        echo "<li>";
        if (file_exists($filePath)) {
            echo "<a href='view_foto.php?id=" . $fileId . "' target='_blank'><img src='" . $filePath . "' alt='" . $fileName . "' /></a>";
        } else {
            echo "<img src='placeholder.png' alt='Gambar " . $fileName . " tidak ditemukan' />"; // Sediakan gambar placeholder jika ada
        }
        echo "<div class='file-details'>";
        echo "<strong>" . $fileName . "</strong>";
        echo "Ukuran: " . round($fileSize / 1024, 2) . " KB<br>";
        echo "Tipe: " . $fileType . "<br>";
        echo "<div class='file-actions'>";
        echo "<a href='view_foto.php?id=" . $fileId . "' target='_blank'>Lihat</a> ";
        echo "<a href='download_foto.php?id=" . $fileId . "'>Download</a> ";
        echo "<a href='delete_foto.php?id=" . $fileId . "' class='delete' onclick='return confirm(\"Yakin hapus foto " . addslashes($fileName) . "?\");'>Delete</a>";
        echo "</div>";
        echo "</div>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Tidak ada foto yang tersedia.</p>";
}

echo "<br><p style='text-align:center;'><a href='form_foto.html'>Kembali ke Form Upload Foto</a></p>";
echo "</body></html>";

mysqli_close($conn_upload);

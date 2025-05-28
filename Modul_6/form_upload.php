<!DOCTYPE html>
<html>

<head>
    <title>Form Upload</title>
</head>

<body>
    <h2>Form Upload Dokumen</h2>
    <form action="proses_upload.php" method="post" enctype="multipart/form-data">
        <label>Nama Lengkap:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Upload Dokumen (PDF):</label><br>
        <input type="file" name="dokumen" accept=".pdf" required><br><br>

        <input type="submit" name="submit" value="Upload">
    </form>
</body>

</html>
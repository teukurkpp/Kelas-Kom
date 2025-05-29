<?php
require_once '../vendor/autoload.php';

use Src\UploadHandler;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadHandler = new UploadHandler();
    $result = $uploadHandler->processFile($_FILES['file']);

    if ($result['success']) {
        echo "<div class='alert alert-success'>{$result['message']}</div>";
    } else {
        echo "<div class='alert alert-danger'>{$result['message']}</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Jadwal Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Unggah Jadwal Kelas</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="file" class="form-label">Pilih file CSV atau Excel</label>
            <input type="file" name="file" id="file" class="form-control" accept=".csv,.xlsx,.xls" required>
            <small class="form-text text-muted">
                File harus memiliki kolom yang sesuai untuk jadwal kelas.
            </small>
        </div>
        <button type="submit" class="btn btn-primary">Unggah File</button>
    </form>
</body>
</html>
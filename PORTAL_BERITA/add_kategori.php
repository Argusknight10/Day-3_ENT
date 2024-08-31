<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->query("SELECT * FROM kategori");
    $kategoriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($kategoriList);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_SESSION['role'] != 'admin') {
            die("Akses ditolak!");
        }
        
        $nama_kategori = $_POST['nama_kategori'];
        
        if (empty($nama_kategori)) {
            die("Nama kategori tidak boleh kosong!");
        }

        $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (:nama_kategori)");
        $stmt->execute(['nama_kategori' => $nama_kategori]);

        header('Location: kategori.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Portal Berita</a>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tambah Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                            <a href="kategori.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
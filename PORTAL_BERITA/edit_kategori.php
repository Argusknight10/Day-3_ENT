<?php
session_start();
require 'config.php';

if ($_SESSION['role'] != 'admin') {
    die("Akses ditolak!");
}

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM kategori WHERE id = :id");
$stmt->execute(['id' => $id]);
$kategoriItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kategoriItem) {
    die("Data tidak ditemukan!");
}

$stmt = $pdo->query("SELECT * FROM kategori");
$kategoriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = $_POST['nama_kategori'];

    $stmt = $pdo->prepare("UPDATE kategori SET nama_kategori = :nama_kategori WHERE id = :id");
    $stmt->execute([
        'nama_kategori' => $nama_kategori,
        'id' => $id
    ]);

    header('Location: kategori.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
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
                        <h5 class="card-title">Edit Berita</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" value="<?php echo htmlspecialchars($kategoriItem['nama_kategori']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
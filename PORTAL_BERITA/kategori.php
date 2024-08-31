<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM kategori ORDER BY id DESC");
$kategoriItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand fs-4 p-3" href="index.php">Portal Berita</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link fs-5 p-3" href="admin_dashboard.php">Dashboard Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-5 p-3" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard Kategori</h1>
        <div class="d-flex justify-content-start mt-3">
            <a href="admin_dashboard.php" class="btn btn-secondary me-2">Berita</a>
            <a href="kategori.php" class="btn btn-primary">Kategori</a>
            <a href="add_kategori.php" class="btn btn-primary mx-2">Tambah Kategori</a>
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Tanggal Buat</th>
                    <th>Tanggal Ubah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; ?>
                <?php foreach ($kategoriItems as $kategori): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($index++); ?></td>
                        <td><?php echo htmlspecialchars($kategori['nama_kategori']); ?></td>
                        <td><?php echo htmlspecialchars($kategori['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($kategori['updated_at']); ?></td>
                        <td>
                            <a href="edit_kategori.php?id=<?php echo $kategori['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a id="hapus" onclick="myFunction()" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function myFunction() {
            var hapusKategori = document.getElementById('hapus');
            if (confirm("Yakin ingin menghapus?") === true) {
                hapusKategori.href="delete_kategori.php?id=<?php echo $kategori['id']; ?>";
            } else {
                hapusKategori.href="kategori.php";
            }
        }
    </script>
    
</body>
</html>

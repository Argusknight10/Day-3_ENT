<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("
    SELECT news.*, kategori.nama_kategori 
    FROM news 
    JOIN kategori ON news.id_kategori = kategori.id 
    ORDER BY news.created_at DESC
");
$newsItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand  fs-4 p-3" href="index.php">Portal Berita</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link  fs-5 p-3" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard Admin</h1>
        <div class="d-flex justify-content-start mt-3">
            <a href="admin_dashboard.php" class="btn btn-primary me-2">Berita</a>
            <a href="kategori.php" class="btn btn-secondary">Kategori</a>
            <a href="add_news.php" class="btn btn-primary mx-3">Tambah Berita</a>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Konten</th>
                    <th>Kategori</th>
                    <th>Tanggal Buat</th>
                    <th>Tanggal Ubah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; ?>
                <?php foreach ($newsItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($index++); ?></td> <!-- Increment ID -->
                        <td>
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Gambar" style="width: 100px;">
                            <?php else: ?>
                                <img src="assets/placeholder.jpeg" alt="Gambar" style="width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($item['content'], 0, 100)); ?>...</td>
                        <td><?php echo htmlspecialchars($item['nama_kategori']); ?></td>
                        <td><?php echo htmlspecialchars($item['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($item['updated_at']); ?></td>
                        <td>
                            <a href="edit_news.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a id="hapusData" onclick="myFunction()" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function myFunction() {
            var deleteData = document.getElementById('hapusData');
            if (confirm("Yakin ingin menghapus?") === true) {
                deleteData.href="delete_news.php?id=<?php echo $item['id']; ?>";
            } else {
                deleteData.href="admin_dashboard.php";
            }
        }
    </script>
</body>

</html>
<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT id, nama_kategori FROM kategori");
$kategoriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_SESSION['role'] != 'admin') {
        die("Akses ditolak!");
    }

    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_kategori = $_POST['id_kategori'];

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = 'assets/' . $imageName;

        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            die("Gagal mengunggah gambar.");
        }
    }

    $stmt = $pdo->prepare("INSERT INTO news (title, content, id_kategori, image) VALUES (:title, :content, :id_kategori, :image)");
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'id_kategori' => $id_kategori,
        'image' => $imagePath,
    ]);

    header('Location: admin_dashboard.php'); 
    exit;
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
                        <h5 class="card-title">Tambah Berita</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar</label>
                                <input type="file" id="image" name="image" class="form-control" onchange="previewImage();" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <img id="imagePreview" src="" alt="Preview Gambar" style="display: none; height:200px; border: 1px solid #ddd; padding: 5px; margin-top: 10px;">
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten</label>
                                <textarea id="content" name="content" class="form-control" rows="6" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select id="kategori" name="id_kategori" class="form-control" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <?php foreach ($kategoriList as $kategori): ?>
                                        <option value="<?php echo htmlspecialchars($kategori['id']); ?>">
                                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                            <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage() {
            const file = document.getElementById('image').files[0];
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>

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

$stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
$stmt->execute(['id' => $id]);
$newsItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$newsItem) {
    die("Data tidak ditemukan!");
}

$stmt = $pdo->query("SELECT id, nama_kategori FROM kategori");
$kategoriList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_kategori = $_POST['id_kategori'];
    $imagePath = $newsItem['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = 'assets/' . $imageName;

        if ($newsItem['image'] && file_exists($newsItem['image'])) {
            unlink($newsItem['image']);
        }

        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            die("Gagal mengunggah gambar.");
        }
    }

    $stmt = $pdo->prepare("UPDATE news SET title = :title, content = :content, id_kategori = :id_kategori, image = :image WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'content' => $content,
        'id_kategori' => $id_kategori,
        'image' => $imagePath,
        'id' => $id
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
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($newsItem['title']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar</label>
                                <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">

                                <?php if ($newsItem['image']): ?>
                                    <img id="oldImagePreview" src="<?php echo htmlspecialchars($newsItem['image']); ?>" alt="Gambar sebelumnya" class="img-thumbnail mt-2" style="max-width: 150px;">
                                    <p class="mt-2" id="oldNamaPreview">Gambar sebelumnya: <?php echo basename($newsItem['image']); ?></p>
                                <?php endif; ?>

                                <img id="newImagePreview" src="" alt="Preview Gambar Baru" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten</label>
                                <textarea id="content" name="content" class="form-control" rows="6" required><?php echo htmlspecialchars($newsItem['content']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select id="kategori" name="id_kategori" class="form-control" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    <?php foreach ($kategoriList as $kategori): ?>
                                        <option value="<?php echo htmlspecialchars($kategori['id']); ?>"
                                            <?php echo ($newsItem['id_kategori'] == $kategori['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($kategori['nama_kategori']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('newImagePreview');
            const oldImage = document.getElementById('oldImagePreview');
            const oldNama = document.getElementById('oldNamaPreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (oldImage) {
                        oldImage.style.display = 'none';
                        oldNama.style.display = 'none';
                    }
                }

                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                if (oldImage) {
                    oldImage.style.display = 'block'; 
                }
            }
        }
    </script>
</body>
</html>


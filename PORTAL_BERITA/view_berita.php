<?php
include 'config.php';

$newsId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = $pdo->prepare('SELECT * FROM news WHERE id = :id');
$query->execute(['id' => $newsId]);
$item = $query->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo '<p>Berita tidak ditemukan.</p>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .news-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .news-title {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .news-date {
            color: #6c757d;
        }
        .news-content {
            margin-top: 20px;
        }
        .btn-back {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 10px;
            border-top: 1px solid #dee2e6;
            text-align: right;
            z-index: 100;
            width: 100%;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand p-3 fs-4" href="index.php">Portal Berita</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Beranda</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-10">
                <div class="card mb-3" >
                    <div class="row g-0">
                        <div class="col-md-4 border">
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top news-image" alt="Gambar Berita">
                            <?php else: ?>
                                <img src="assets/placeholder.jpeg" class="card-img-top news-image" alt="Gambar Berita">
                            <?php endif; ?>
                            <h5 class="card-title news-title m-3 p-2 fs-3"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="news-date m-3 p-2"><?php echo htmlspecialchars(date('d M Y', strtotime($item['created_at']))); ?></p>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="news-content">
                                    <p><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="btn-back">
                        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Portal Berita. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<

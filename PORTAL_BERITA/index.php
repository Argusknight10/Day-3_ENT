<?php
session_start();
require 'config.php';

$loggedIn = isset($_SESSION['user_id']);

$stmt = $pdo->query("SELECT nama_kategori FROM kategori");
$kategoriList = $stmt->fetchAll(PDO::FETCH_COLUMN);

$kategoriFilter = isset($_GET['kat']) ? $_GET['kat'] : '';

$sql = "SELECT * FROM news";
$params = [];

if ($kategoriFilter) {
    $sql .= " WHERE id_kategori = (SELECT id FROM kategori WHERE nama_kategori = :kategori)";
    $params['kategori'] = $kategoriFilter;
}

$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$newsItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$carouselItems = array_slice($newsItems, 0, 3);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand p-3 fs-4" href="index.php">Portal Berita</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <?php if ($loggedIn): ?>
                <?php if ($_SESSION['role'] !== 'admin'): ?>
                    <?php foreach ($kategoriList as $kategori): ?>
                        <li class="nav-item">
                            <a class="nav-link fs-5 p-3" href="index.php?kat=<?php echo urlencode($kategori); ?>">
                                <?php echo htmlspecialchars($kategori); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
                <li class="nav-item dropdown d-flex align-items-center">
                    <a class="nav-link fs-5 p-3" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list p-3 fs-5 bg-light text-dark"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link fs-5 p-3" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
    <div class="container mt-4">
        
        <div id="newsCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($carouselItems as $index => $item): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" >
                        <?php if (!empty($item['image'])): ?>
                            <a href="view_berita.php?id=<?php echo $item['id']; ?>"><img src="<?php echo htmlspecialchars($item['image']); ?>" class="d-block w-100" style="object-fit: cover; height: 700px;" alt="Gambar Berita"></a>
                        <?php else: ?>
                            <img src="assets/placeholder.jpeg" class="d-block w-100" style="object-fit: cover; height: 700px;" alt="Gambar Berita">
                            <a href="view_berita.php?id=<?php echo $item['id']; ?>"></a>
                        <?php endif; ?>
                        <div class="carousel-caption d-none d-md-block">
                            <h5 style="font-size:30px;"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p><?php echo htmlspecialchars(substr($item['content'], 0, 100)); ?>...</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
            </button>
        </div>

        <div class="d-flex">
            <div class="col-9 p-3" style="border-right: 1px solid grey;">
                <div class="row">
                    <?php if (empty($newsItems)): ?>
                        <p class="text-center">Tidak ada berita yang tersedia untuk kategori ini.</p>
                    <?php else: ?>
                        <?php foreach ($newsItems as $item): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;" alt="Gambar Berita">
                                    <?php else: ?>
                                        <img src="assets/placeholder.jpeg" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;" alt="Gambar Berita">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($item['content'], 0, 100)); ?>...</p>
                                        <a href="view_berita.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">Lihat Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-3 mx-3 ">
                <video width="100%" controls>
                    <source src="assets/Hymne PENS _ PENS.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>

    <footer class="footer text-center bg-dark p-3 mt-5">
        <div class="container">
            <p class="text-light mb-0">&copy; <?php echo date('Y'); ?> Portal Berita. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

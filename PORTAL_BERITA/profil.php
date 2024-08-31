<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    
    $dataUpdated = false;
    
    if ($username !== $user['username']) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->execute(['username' => $username, 'id' => $userId]);
        $dataUpdated = true;
    }
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['profile_image']['tmp_name'];
        $imageName = basename($_FILES['profile_image']['name']);
        $imagePath = 'assets/' . $imageName;
        
        if (!move_uploaded_file($imageTmpPath, $imagePath)) {
            die("Gagal mengunggah gambar.");
        }

        if ($imagePath !== $user['profile_image']) {
            $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
            $stmt->execute(['profile_image' => $imagePath, 'id' => $userId]);
            $dataUpdated = true;
        }
    }

    if ($dataUpdated) {
        header("Location: index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand fs-5 p-3" href="index.php">Portal Berita</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item fs-5 p-3">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Profil Saya</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="profile_image" class="form-label">Gambar Profil</label>
                <input type="file" id="profile_image" name="profile_image" class="form-control" onchange="previewProfileImage(event)">
                
                <div class="mt-2">
                    <?php
                    $profileImage = $user['profile_image'] ? $user['profile_image'] : 'assets/default.png';
                    ?>
                    <img id="profilePreview" src="<?php echo htmlspecialchars($profileImage); ?>" alt="Gambar Profil" class="img-thumbnail" style="max-width: 150px;">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script>
        function previewProfileImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require 'config.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak!");
}

if (!isset($_GET['id'])) {
    die("ID berita tidak ditemukan!");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT image FROM news WHERE id = :id");
$stmt->execute(['id' => $id]);
$newsItem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$newsItem) {
    die("Berita tidak ditemukan!");
}

if (!empty($newsItem['image'])) {
    $imagePath = 'assets/' . $newsItem['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
$stmt->execute(['id' => $id]);

header('Location: admin_dashboard.php');
exit;
?>

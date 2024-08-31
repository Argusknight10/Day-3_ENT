<?php
$host = 'localhost';
$dbname = 'portal_berita';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
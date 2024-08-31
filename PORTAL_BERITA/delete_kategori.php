<?php
    session_start();
    require 'config.php';

    if ($_SESSION['role'] !== 'admin') {
        die("Akses ditolak!");
    }

    if (!isset($_GET['id'])) {
        die("ID kategori tidak ditemukan!");
    }

    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = :id");
    $stmt->execute(['id' => $id]);

    header('Location: kategori.php');
    exit;
?>

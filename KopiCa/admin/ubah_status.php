<?php
session_start();
include '../config.php';
$conn = $koneksi;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pesanan'], $_POST['status_baru'])) {
    
    $id_pesanan = (int)$_POST['id_pesanan'];
    $status_baru = mysqli_real_escape_string($conn, strtolower($_POST['status_baru']));

    $allowed_statuses = ['pending', 'diproses', 'selesai', 'dibatalkan']; 

    if (in_array($status_baru, $allowed_statuses)) {
        
        $query = "UPDATE pesanan SET status = '$status_baru' WHERE id_pesanan = $id_pesanan";
        
        if (mysqli_query($conn, $query)) {
            $message = "Status pesanan #$id_pesanan berhasil diubah menjadi " . ucfirst($status_baru) . ".";
            header("Location: order_detail.php?id=$id_pesanan&success=" . urlencode($message));
            exit;
        } else {
            $error = "Gagal mengubah status: " . mysqli_error($conn);
            header("Location: order_detail.php?id=$id_pesanan&error=" . urlencode($error));
            exit;
        }
        
    } else {
        $error = "Status yang dikirim tidak valid.";
        header("Location: order_detail.php?id=$id_pesanan&error=" . urlencode($error));
        exit;
    }
} else {
    header("Location: kelola_pesanan.php");
    exit;
}
?>
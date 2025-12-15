<?php
include 'auth_check.php';
$conn = $koneksi; 

$tabel = $_GET['tabel'] ?? '';
$id = (int) ($_GET['id'] ?? 0); 

$allowed_tables = ['kategori_menu', 'menu', 'users', 'pesanan', 'detail_pesanan'];

if ($id > 0 && in_array($tabel, $allowed_tables)) {
    $pk_column = "id_" . str_replace('_menu', '', $tabel);
    if ($tabel === 'detail_pesanan') $pk_column = 'id_detail'; 
    if ($tabel === 'users') $pk_column = 'id_user';
    if ($tabel === 'pesanan') $pk_column = 'id_pesanan';
    
    $stmt = $koneksi->prepare("DELETE FROM $tabel WHERE $pk_column = ?");
    
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus'); history.back();</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $conn->error . "'); history.back();</script>";
    }
    $stmt->close();
    
} else {
    echo "<script>alert('Parameter tidak valid atau tidak diizinkan'); history.back();</script>";
}
?>
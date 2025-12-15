<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM users WHERE id_user = $id");

header("Location: kelola_akun.php");
exit;
?>

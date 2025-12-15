<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password_hash, nama, email, role)
              VALUES ('$username', '$password', '$nama', '$email', '$role')";

    if (mysqli_query($conn, $query)) {
        header("Location: kelola_akun.php");
        exit;
    } else {
        $msg = "Gagal menambah akun!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Tambah Akun Baru</h3>

    <form method="POST" class="card p-4 shadow-sm">
        <p class="text-danger"><?= $msg ?></p>

        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control mb-2" required>

        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control mb-2" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control mb-2">

        <label class="form-label">Role</label>
        <select name="role" class="form-control mb-2" required>
            <option value="customer">customer</option>
            <option value="admin">admin</option>
        </select>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" required>

        <button class="btn btn-success">Simpan</button>
        <a href="Kelola_akun.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>

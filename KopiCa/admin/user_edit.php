<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user = $id");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("User tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = "UPDATE users SET username='$username', nama='$nama', email='$email', role='$role', password_hash='$password' WHERE id_user=$id";
    } else {
        $update = "UPDATE users SET username='$username', nama='$nama', email='$email', role='$role' WHERE id_user=$id";
    }

    mysqli_query($conn, $update);
    header("Location: kelola_akun.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <h3>Edit Akun</h3>

    <form method="POST" class="card p-4 shadow-sm">

        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control mb-2" value="<?= $user['username'] ?>" required>

        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control mb-2" value="<?= $user['nama'] ?>" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control mb-2" value="<?= $user['email'] ?>">

        <label class="form-label">Role</label>
        <select name="role" class="form-control mb-2">
            <option value="customer" <?= $user['role']=='customer'?'selected':'' ?>>customer</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>admin</option>
        </select>

        <label class="form-label">Password Baru (Opsional)</label>
        <input type="password" name="password" class="form-control mb-3">

        <button class="btn btn-primary">Update</button>
        <a href="kelola_akun.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>

<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$query = "SELECT * FROM users WHERE role != 'admin' ORDER BY id_user DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Akun | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<h2 class="mb-4 text-center">Kelola Akun Pengguna</h2>

<div class="mb-3 d-flex justify-content-between">
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
    <a href="user_add.php" class="btn btn-primary">+ Tambah Akun</a>
</div>


    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <span class="badge <?= $row['role'] == 'admin' ? 'bg-danger' : 'bg-success' ?>">
                                <?= $row['role'] ?>
                            </span>
                        </td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="user_edit.php?id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="user_delete.php?id=<?= $row['id_user'] ?>"
                               onclick="return confirm('Hapus akun ini?')"
                               class="btn btn-danger btn-sm">
                               Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>

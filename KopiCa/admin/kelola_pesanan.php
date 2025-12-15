<?php
session_start();
include '../config.php'; 
$conn = $koneksi;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

$query_pesanan = "
    SELECT p.*, u.username 
    FROM pesanan p
    JOIN users u ON p.id_user = u.id_user
    WHERE DATE(p.tanggal_pesan) = CURDATE() 
    ORDER BY p.tanggal_pesan DESC
";
$result_pesanan = mysqli_query($conn, $query_pesanan);

$status_colors = [
    'pending' => 'warning', 
    'selesai' => 'success',
    'diproses' => 'info'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan Hari Ini - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f8f5f0; }
        .title { color: #4b2e1e; font-size: 28px; font-weight: bold; }
        .container { max-width: 1200px; margin: 40px auto; }
        .table-custom th { background-color: #4A2C2A; color: white; }
    </style>
</head>

<body>

<div class="container">
    <h2 class="title mb-4">🛒 Kelola Pesanan Hari Ini (<?= date('d M Y') ?>)</h2>
    
    <div class="d-flex justify-content-between mb-3">
    <a href="index.php" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>

    <a href="riwayat_pesanan.php" class="btn btn-sm btn-dark">
        <i class="fas fa-history me-1"></i> Riwayat Pesanan
    </a>
</div>
    <div class="card shadow-sm">

        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0 table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Tanggal Pesan</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result_pesanan) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_pesanan)): 
                            $status_class = $status_colors[strtolower($row['status'])] ?? 'secondary';
                        ?>
                        <tr>
                            <td><?= $row['id_pesanan'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($row['tanggal_pesan'])) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><?= $row['metode_pembayaran'] ?></td>
                            <td>
                                <span class="badge bg-<?= $status_class ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            
                            <td>
                                <a href="order_detail.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-sm btn-info text-white">
                                    Detail / Ubah Status
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center p-4">Belum ada pesanan yang masuk hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
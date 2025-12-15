<?php
session_start();
include '../config.php'; 
$conn = $koneksi;

if (!isset($_SESSION['user']['id_user'])) {
    echo "<script>window.location='login.php';</script>";
    exit;
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Pesanan tidak valid.'); window.history.back();</script>";
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$id_pesanan = (int)$_GET['id'];

$stmt_pesanan = $koneksi->prepare("SELECT * FROM pesanan WHERE id_pesanan = ? AND id_user = ?");
$stmt_pesanan->bind_param("ii", $id_pesanan, $id_user);
$stmt_pesanan->execute();
$result_pesanan = $stmt_pesanan->get_result();
$data_pesanan = $result_pesanan->fetch_assoc();

if (!$data_pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan atau Anda tidak memiliki akses.'); window.location='orders.php';</script>";
    exit;
}

$query_detail = "
    SELECT dp.*, m.nama_menu 
    FROM detail_pesanan dp
    JOIN menu m ON dp.id_menu = m.id_menu
    WHERE dp.id_pesanan = ?
";
$stmt_detail = $koneksi->prepare($query_detail);
$stmt_detail->bind_param("i", $id_pesanan);
$stmt_detail->execute();
$result_detail = $stmt_detail->get_result();

$page_title = "Detail Pesanan #" . $id_pesanan;

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - KopiCa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --color-coffee-dark: #4A2C2A; 
            --color-coffee-medium: #795548; 
            --color-cream-light: #F8F5F2; 
        }
        body { background-color: var(--color-cream-light); color: var(--color-coffee-dark); font-family: Arial, sans-serif; }
        .card-box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .table-item th { background-color: var(--color-coffee-dark); color: white; }
        .btn-dark { background-color: var(--color-coffee-medium); border-color: var(--color-coffee-medium); }
        .btn-dark:hover { background-color: #5d4037; border-color: #5d4037; }
    </style>
</head>
<body>
<section class="container my-5" style="max-width: 900px;">
    
    <h3 class="mb-4" style="color: var(--color-coffee-dark); border-bottom: 2px solid var(--color-coffee-medium); padding-bottom: 10px;">
        <i class="fas fa-receipt me-2"></i> Detail Pesanan #<?= $id_pesanan ?>
    </h3>

    <div class="card-box mb-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Informasi Pesanan</h5>

        <table class="table table-bordered">
            <tr>
                <th style="width: 180px;">Status</th>
                <td>
                    <?php
                    $status = strtolower($data_pesanan['status']);
                    $badge = ['pending' => 'secondary', 'diproses' => 'info', 'selesai' => 'success', 'dibatalkan' => 'danger'][$status] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?= $badge ?> fs-6"><?= ucfirst($status) ?></span>
                </td>
            </tr>
            <tr>
                <th>Tanggal Pesan</th>
                <td><?= date('d M Y H:i', strtotime($data_pesanan['tanggal_pesan'])) ?></td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td><?= e($data_pesanan['metode_pembayaran']) ?></td>
            </tr>
            <tr>
                <th>Total Pembayaran</th>
                <td class="fw-bold text-success fs-5">Rp <?= number_format($data_pesanan['total_harga'], 0, ',', '.') ?></td>
            </tr>
        </table>
    </div>

    <div class="card-box">
        <h5 class="fw-bold mb-3"><i class="fas fa-list-alt me-2"></i> Daftar Item</h5>

        <table class="table table-bordered table-striped align-middle">
            <thead class="text-center table-item">
                <tr>
                    <th>#</th>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $no = 1;
                if ($result_detail->num_rows > 0): 
                    while ($row = $result_detail->fetch_assoc()): ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td class="text-start"><?= htmlspecialchars($row['nama_menu']) ?></td>
                            <td><?= $row['qty'] ?></td>
                            <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; 
                else: ?>
                    <tr>
                        <td colspan="5" class="text-center p-3">
                            Tidak ada detail item dalam pesanan ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="4" class="text-end fw-bold">Grand Total</td>
                    <td class="text-center fw-bold">Rp <?= number_format($data_pesanan['total_harga'], 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="mt-4 text-end">
        <a href="orders.php" class="btn btn-dark">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Riwayat
        </a>
    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
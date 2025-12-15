<?php
session_start();
include '../config.php';
$conn = $koneksi; 

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID Pesanan tidak ditemukan!";
    exit;
}

$id_pesanan = (int)$_GET['id'];

$query_pesanan = "SELECT * FROM pesanan WHERE id_pesanan = $id_pesanan";
$result_pesanan = mysqli_query($conn, $query_pesanan);
$data_pesanan = mysqli_fetch_assoc($result_pesanan);

if (!$data_pesanan) {
    echo "Pesanan dengan ID $id_pesanan tidak ditemukan.";
    exit;
}

$status_sekarang = strtolower($data_pesanan['status']);

$query_detail = "
    SELECT dp.*, m.nama_menu 
    FROM detail_pesanan dp
    JOIN menu m ON dp.id_menu = m.id_menu
    WHERE dp.id_pesanan = $id_pesanan
";
$result_detail = mysqli_query($conn, $query_detail);

if (!$result_detail) {
    die("Query Detail Gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: #f8f5f0;
        }
        .title {
            color: #4b2e1e;
            font-size: 28px;
            font-weight: bold;
        }
        .card-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .back-btn {
            background: #8b5e3c;
            color: white;
            border-radius: 6px;
            padding: 6px 14px;
            text-decoration: none;
        }
        .back-btn:hover {
            opacity: .8;
            color: white;
        }
        .table-item th { background-color: #4b2e1e; color: white; }
    </style>
</head>

<body>

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h2 class="title mb-4">📝 Detail Pesanan #<?= $id_pesanan ?></h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="card-box mb-4 p-3 bg-light border border-secondary" id="ubah-status">
        <h5 class="fw-bold mb-3"><i class="fas fa-edit me-2"></i> Ubah Status Pesanan</h5>
        <form method="POST" action="ubah_status.php">
            <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
            <div class="d-flex align-items-center">
                <label for="status_baru" class="form-label me-3 mb-0 fw-bold">Status Saat Ini: <span class="badge bg-info"><?= ucfirst($status_sekarang) ?></span></label>
                
                <select name="status_baru" id="status_baru" class="form-select me-3" style="width: 200px;">
                    <option value="pending" <?= ($status_sekarang === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="diproses" <?= ($status_sekarang === 'diproses') ? 'selected' : '' ?>>Diproses</option>
                    <option value="selesai" <?= ($status_sekarang === 'selesai') ? 'selected' : '' ?>>Selesai</option>
                    <option value="dibatalkan" <?= ($status_sekarang === 'dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Update Status
                </button>
            </div>
            <small class="text-muted mt-2 d-block">Pilih status baru yang sesuai untuk pesanan ini.</small>
        </form>
    </div>

    <div class="card-box mb-4">
        <h5 class="fw-bold mb-3">📌 Informasi Pesanan</h5>

        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">ID Pesanan</th>
                <td><?= $data_pesanan['id_pesanan'] ?></td>
            </tr>
            <tr>
                <th>ID User</th>
                <td><?= $data_pesanan['id_user'] ?></td>
            </tr>
            <tr>
                <th>Tanggal Pesan</th>
                <td><?= $data_pesanan['tanggal_pesan'] ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= ucfirst($data_pesanan['status']) ?></td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td><?= $data_pesanan['metode_pembayaran'] ?></td>
            </tr>
        </table>
    </div>

    <div class="card-box">
        <h5 class="fw-bold mb-3">🍽️ Item Pesanan</h5>
        <table class="table table-bordered table-striped">
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
                if (mysqli_num_rows($result_detail) > 0) {
                    while ($row = mysqli_fetch_assoc($result_detail)) { ?>
                        <tr class="text-center">
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                            <td><?= $row['qty'] ?></td>
                            <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                <?php }
                } else { ?>
                    <tr>
                        <td colspan="5" class="text-center p-3">
                            Tidak ada detail pesanan. (Mungkin pesanan lama tanpa detail).
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="text-end fw-bold fs-5 mt-3">
            Total: Rp <?= number_format($data_pesanan['total_harga'], 0, ',', '.') ?>
        </div>

    </div>

    <div class="mt-4">
        <a href="kelola_pesanan.php" class="back-btn">⬅ Kembali ke Daftar Pesanan</a>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
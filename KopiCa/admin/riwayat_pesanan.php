<?php
session_start();
include '../config.php';

$where = [];
$params = "";

if (isset($_GET['tanggal']) && $_GET['tanggal'] !== '') {
    $tanggal = $_GET['tanggal'];
    $where[] = "DATE(tanggal_pesan) = '$tanggal'";
    $params .= "&tanggal=$tanggal";
}

if (isset($_GET['cari_user']) && $_GET['cari_user'] !== '') {
    $cari_user = $_GET['cari_user'];
    $where[] = "id_user LIKE '%$cari_user%'";
    $params .= "&cari_user=$cari_user";
}

$where_sql = "";
if (count($where) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$query = "SELECT * FROM pesanan $where_sql ORDER BY tanggal_pesan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="background: #f8f5f0;">
    <div class="container" style="max-width: 1200px; margin: 40px auto;">

        <h2 class="fw-bold mb-4" style="color:#4b2e1e; font-size: 28px;">
            📦 Riwayat Pesanan
        </h2>

        <a href="index.php" 
           class="btn btn-secondary mb-3"
           style="background:#6b7280; border:none;">
            ⬅ Kembali 
        </a>

        <div style="
            background:white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        ">

            <form method="GET" class="row g-3 mb-4">

                <div class="col-md-4">
                    <label class="form-label">Filter Tanggal</label>
                    <input 
                        type="date" 
                        name="tanggal" 
                        class="form-control"
                        value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : '' ?>"
                    >
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cari ID User</label>
                    <input 
                        type="text" 
                        name="cari_user" 
                        class="form-control"
                        placeholder="Masukkan ID User..."
                        value="<?= isset($_GET['cari_user']) ? $_GET['cari_user'] : '' ?>"
                    >
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-dark w-100">🔍 Terapkan Filter</button>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead style="background: #1f2937; color:white; text-align:center;">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>ID User</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Metode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $row['tanggal_pesan'] ?></td>
                                <td><?= $row['id_user'] ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>

                                <td>
                                    <span style="
                                        background:#9ca3af;
                                        color:white;
                                        padding:5px 12px;
                                        border-radius:5px;
                                        font-size: 13px;
                                    ">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>

                                <td><?= $row['metode_pembayaran'] ?></td>

                                <td>
                                    <a href="order_detail.php?id=<?= $row['id_pesanan'] ?>" 
                                       class="btn btn-sm"
                                       style="
                                            background:#8b5e3c;
                                            color:white;
                                            padding:4px 10px;
                                            font-size:13px;
                                            border-radius:6px;
                                       ">
                                        🔍 Detail
                                    </a>
                                </td>
                            </tr>
                    <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7" class="text-center p-3">
                                Tidak ada pesanan ditemukan.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>

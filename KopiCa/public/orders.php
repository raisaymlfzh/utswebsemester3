<?php
include '../config.php'; 
$conn = $koneksi; 

if (!isset($_SESSION['user'])) {
    $_SESSION['login_error'] = 'Anda harus login untuk melihat riwayat pesanan.';
    echo "<script>window.location='login.php';</script>";
    exit;
}

$id_user = $_SESSION['user']['id_user'];

$stmt = $koneksi->prepare("SELECT * FROM pesanan WHERE id_user = ? ORDER BY tanggal_pesan DESC");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$page_title = "Pesanan Saya";
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
            --color-white: #ffffff;
        }
        body { background-color: var(--color-cream-light); color: var(--color-coffee-dark); font-family: Arial, sans-serif; }
        
        .table-dark { background-color: var(--color-coffee-dark) !important; color: var(--color-cream-light); }
        .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: #f0ebe8; }
        
        h3 {
            color: var(--color-coffee-dark);
            border-bottom: 2px solid var(--color-coffee-medium);
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .btn-dark {
            background-color: var(--color-coffee-medium);
            border-color: var(--color-coffee-medium);
        }
        .btn-dark:hover {
            background-color: #5d4037;
            border-color: #5d4037;
        }
    </style>
</head>
<body>

<section class="container my-5">
  <h3><i class="fas fa-box-open me-2"></i> Pesanan Saya</h3>

  <div class="mb-4">
    <a href="../public/menu.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left"></i> Kembali ke Menu
    </a>
</div>


  <?php if ($result->num_rows == 0): ?>
    <div class="alert alert-info shadow-sm">Belum ada pesanan. <a href="index.php">Belanja sekarang</a></div>
  <?php else: ?>
    <div class="table-responsive shadow-sm rounded-3 overflow-hidden">
        <table class="table table-bordered table-striped align-middle mb-0">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Tanggal</th>
              <th>Total Harga</th>
              <th>Status</th>
              <th>Metode</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            while($p = $result->fetch_assoc()):
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= date('d/m/Y H:i', strtotime($p['tanggal_pesan'])) ?></td>
              <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
              <td>
                <?php
                  $badge = [
                    'pending' => 'secondary',
                    'diproses' => 'info',
                    'selesai' => 'success',
                    'dibatal' => 'danger'
                  ][$p['status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $badge ?>"><?= ucfirst($p['status']) ?></span>
              </td>
              <td><?= e($p['metode_pembayaran']) ?></td>
              <td>
                  <a href="order_detail.php?id=<?= $p['id_pesanan'] ?>" class="btn btn-sm btn-dark">
                      <i class="fas fa-info-circle"></i> Detail
                  </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
    </div>
  <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

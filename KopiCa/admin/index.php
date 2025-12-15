<?php 
include '../config.php'; 
$conn = $koneksi;
include '../public/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopiCa</title>
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    
    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        background-color: var(--color-cream-light);
        color: var(--color-coffee-dark);
        font-family: Arial, sans-serif;
    }

    .main-content {
        flex: 1; 
    }

    footer {
        margin-top: auto; 
    }

    :root {
        --color-coffee-dark: #4A2C2A;
        --color-coffee-medium: #795548;
        --color-cream-light: #F8F5F2;
        --color-white: #ffffff;
    }

    header.kopica-bg {
        background-color: var(--color-coffee-dark) !important;
        border-bottom: 4px solid var(--color-coffee-medium);
    }

    .admin-card-container {
        padding-top: 50px;
        padding-bottom: 20px;
    }

    .dashboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .dashboard-card .card-body {
        padding: 30px;
        background-color: var(--color-white);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dashboard-card h4 {
        color: var(--color-coffee-medium);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .dashboard-card .btn-dark {
        background-color: var(--color-coffee-dark);
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s;
        margin-top: 15px;
    }

    .dashboard-card .btn-dark:hover {
        background-color: #6d4c4a;
    }

    h3 {
        color: var(--color-coffee-dark);
        border-bottom: 2px solid var(--color-coffee-medium);
        padding-bottom: 10px;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .table-dark {
        background-color: var(--color-coffee-dark) !important;
        color: var(--color-cream-light);
    }

    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #f0ebe8;
    }

    .btn-primary {
        background-color: var(--color-coffee-medium);
        border-color: var(--color-coffee-medium);
    }

    .btn-primary:hover {
        background-color: #5d4037;
        border-color: #5d4037;
    }

    .btn-warning {
        background-color: #FFC107;
        border-color: #FFC107;
    }

    .btn-danger {
        background-color: #DC3545;
        border-color: #DC3545;
    }

</style>

</head>
<body>

<div class="container my-5 admin-card-container main-content">
  <h2 class="text-center" style="color: var(--color-coffee-dark); font-weight: 800;">Dashboard Admin</h2>
  <p class="text-center text-muted mb-5">Selamat datang, <?= e($_SESSION['user']['username'] ?? 'Admin') ?>. Kelola data KopiCa di sini.</p>

  <div class="row justify-content-center g-4">
    <div class="col-md-5 col-lg-4 mb-4">
      <div class="card dashboard-card">
        <div class="card-body text-center">
          <div>
            <h4><i class="fas fa-tags me-2"></i> Kategori Menu</h4>
            <p class="text-muted small">Tambah, edit, atau hapus kategori menu (misal: Kopi, Makanan, Minuman Non-Kopi).</p>
          </div>
          <a href="../admin/kategori_menu.php" class="btn btn-dark btn-sm">Kelola Kategori</a>
        </div>
      </div>
    </div>
 
    <div class="col-md-5 col-lg-4 mb-4">
      <div class="card dashboard-card">
        <div class="card-body text-center">
          <div>
            <h4><i class="fas fa-mug-hot me-2"></i> Menu Kopi & Makanan</h4>
            <p class="text-muted small">Kelola daftar menu, harga, stok, deskripsi, dan gambar produk.</p>
          </div>
          <a href="../admin/daftar_menu.php" class="btn btn-dark btn-sm">Kelola Menu</a>
        </div>
      </div>
    </div>

    <div class="col-md-5 col-lg-4 mb-4">
      <div class="card dashboard-card">
        <div class="card-body text-center">
          <div>
            <h4><i class="fas fa-file-invoice-dollar me-2"></i> Laporan Pesanan</h4>
            <p class="text-muted small">Lihat riwayat pesanan, total pendapatan, dan status pengiriman.</p>
          </div>
          <a href="../admin/kelola_pesanan.php" class="btn btn-dark btn-sm">Lihat Pesanan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include '../public/footer.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
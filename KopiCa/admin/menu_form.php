<?php
include '../config.php';
$conn = $koneksi; 

$id_menu = $_GET['id'] ?? '';
$nama_menu = '';
$harga = '';
$stok = '';
$id_kategori = '';
$deskripsi = '';

$gambar_url = ''; 
$page_title = $id_menu ? 'Edit Menu' : 'Tambah Menu Baru'; 
$error = '';
$success = '';


if ($id_menu) {
    $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id_menu=?");
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close(); 

    if ($data) {
        $nama_menu = $data['nama_menu'];
        $harga = $data['harga'];
        $stok = $data['stok'];
        $id_kategori = $data['id_kategori'];
        $deskripsi = $data['deskripsi'];
        $gambar_url = $data['gambar']; 
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = trim($_POST['nama_menu'] ?? '');
    $harga = (float) ($_POST['harga'] ?? 0);
    $stok = (int) ($_POST['stok'] ?? 0);
    $id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $gambar_url_baru = trim($_POST['gambar_url'] ?? ''); 

    if (!$id_menu && empty($gambar_url_baru)) {
        $error = "URL Gambar harus diisi untuk menu baru.";
    }
    
    if (empty($error)) {
        if ($id_menu) {
            $stmt = $koneksi->prepare("UPDATE menu SET nama_menu=?, harga=?, stok=?, id_kategori=?, deskripsi=?, gambar=? WHERE id_menu=?");
            $stmt->bind_param("sdisssi", $nama_menu, $harga, $stok, $id_kategori, $deskripsi, $gambar_url_baru, $id_menu);
        } else {
            $stmt = $koneksi->prepare("INSERT INTO menu (nama_menu, harga, stok, id_kategori, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdisss", $nama_menu, $harga, $stok, $id_kategori, $deskripsi, $gambar_url_baru);
        }

        if ($stmt->execute()) {
            $success = "Data berhasil disimpan!";
            echo "<script>alert('Data berhasil disimpan'); window.location='daftar_menu.php';</script>";
            exit;
        } else {
            $error = "Terjadi kesalahan saat menyimpan data: " . $koneksi->error;
        }
        $stmt->close();
    }
}

$kategori_result = $koneksi->query("SELECT id_kategori, nama_kategori FROM kategori_menu ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Admin KopiCa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --color-coffee-dark: #4A2C2A; 
            --color-coffee-medium: #795548; 
            --color-cream-light: #F8F5F2; 
            --color-white: #ffffff;
        }
        
        body {
            background-color: var(--color-cream-light); 
            color: var(--color-coffee-dark); 
            font-family: Arial, sans-serif;
        }

        .form-card-wrapper {
            max-width: 800px;
            margin: 50px auto;
            background-color: var(--color-white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h3 { 
            color: var(--color-coffee-dark);
            border-bottom: 2px solid var(--color-coffee-medium);
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-weight: 700;
        }

        .btn-submit { 
            background-color: var(--color-coffee-dark); 
            border-color: var(--color-coffee-dark);
            transition: background-color 0.3s; 
        }
        .btn-submit:hover { 
            background-color: #6d4c4a; 
            border-color: #6d4c4a;
        }
        
        .btn-secondary {
             background-color: var(--color-coffee-medium); 
             border-color: var(--color-coffee-medium);
             transition: background-color 0.3s; 
        }
        .btn-secondary:hover {
            background-color: #5d4037;
            border-color: #5d4037;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
        }

        .current-image {
            max-height: 150px;
            width: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
  <div class="form-card-wrapper">
    <h3><?= $page_title ?></h3>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <form method="post">
      
      <div class="mb-3">
        <label for="nama_menu" class="form-label fw-bold">Nama Menu</label>
        <input type="text" name="nama_menu" id="nama_menu" value="<?= e($nama_menu) ?>" class="form-control" required placeholder="Cth: Cappuccino Dingin, Nasi Goreng Spesial">
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="id_kategori" class="form-label fw-bold">Kategori</label>
          <select name="id_kategori" id="id_kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while ($kat = $kategori_result->fetch_assoc()): ?>
                <option value="<?= $kat['id_kategori'] ?>" <?= $id_kategori == $kat['id_kategori'] ? 'selected' : '' ?>>
                    <?= e($kat['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
          </select>
        </div>
        
        <div class="col-md-3 mb-3">
          <label for="harga" class="form-label fw-bold">Harga (Rp)</label>
          <input type="number" name="harga" id="harga" value="<?= e($harga) ?>" class="form-control" required min="1000">
        </div>

        <div class="col-md-3 mb-3">
          <label for="stok" class="form-label fw-bold">Stok</label>
          <input type="number" name="stok" id="stok" value="<?= e($stok) ?>" class="form-control" required min="0">
        </div>
      </div>
      
      <div class="mb-3">
        <label for="deskripsi" class="form-label fw-bold">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Jelaskan bahan-bahan dan rasa menu ini..."><?= e($deskripsi) ?></textarea>
      </div>
      
      <div class="mb-3">
        <label for="gambar_url" class="form-label fw-bold">URL Gambar Produk</label>
        <input type="text" name="gambar_url" id="gambar_url" value="<?= e($gambar_url) ?>" class="form-control" placeholder="Contoh: https://linkgambar.com/menu123.jpg">
        <small class="form-text text-muted">Masukkan link gambar produk.</small>

        <?php if ($gambar_url): ?>
            <div class="mt-2">
                <p class="mb-1 small">Gambar Saat Ini:</p>
                <img src="<?= e($gambar_url) ?>" class="current-image" alt="Gambar Saat Ini">
            </div>
        <?php endif; ?>
      </div>
      
      <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-submit text-white"><i class="fas fa-save"></i> Simpan Menu</button>
        <a href="../admin/daftar_menu.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
include '../config.php';
$conn = $koneksi;

$id = $_GET['id'] ?? '';
$nama = '';
$page_title = $id ? 'Edit Kategori' : 'Tambah Kategori Baru'; 


if ($id) {
    $stmt = $conn->prepare("SELECT * FROM kategori_menu WHERE id_kategori=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $nama = $data['nama_kategori'] ?? '';
    $stmt->close(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama_kategori']);
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE kategori_menu SET nama_kategori=? WHERE id_kategori=?");
        $stmt->bind_param("si", $nama, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO kategori_menu (nama_kategori) VALUES (?)");
        $stmt->bind_param("s", $nama);
    }
    
    $stmt->execute();
    $stmt->close(); 
    echo "<script>alert('Data berhasil disimpan'); window.location='kategori_menu.php';</script>";
}
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
            max-width: 500px;
            margin-top: 50px;
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
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-success { 
            background-color: var(--color-coffee-dark); 
            border-color: var(--color-coffee-dark);
            transition: background-color 0.3s; 
            margin-right: 10px; 
        }
        .btn-success:hover { 
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
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
  <div class="form-card-wrapper">
    <h3><i class="fas fa-tags me-2"></i> <?= $page_title ?></h3>
    
    <form method="post">
      <div class="mb-4">
        <label for="nama_kategori" class="form-label fw-bold">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" value="<?= e($nama) ?>" class="form-control" required placeholder="Contoh: Kopi Panas, Makanan Ringan">
      </div>
      
      <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
        <a href="../admin/kategori_menu.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
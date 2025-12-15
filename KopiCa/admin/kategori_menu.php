<?php 
include '../config.php'; 

$conn = $koneksi; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Admin KopiCa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
   
    <style>
        :root {
            --color-coffee-dark: #4A2C2A; 
            --color-coffee-medium: #795548; 
            --color-cream-light: #F8F5F2;
            --color-back-btn: #f0ebe8; 
            --color-white: #ffffff;
        }
        
        body {
            background-color: var(--color-cream-light); 
            color: var(--color-coffee-dark); 
            font-family: Arial, sans-serif;
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

        .btn-back-custom {
            background-color: var(--color-back-btn);
            color: var(--color-coffee-dark);
            border-color: #ccc;
            transition: background-color 0.3s;
        }
        .btn-back-custom:hover {
            background-color: #ddd;
            color: var(--color-coffee-dark);
        }
        
        .btn-warning {
            background-color: #FFC107;
            border-color: #FFC107;
            color: var(--color-coffee-dark);
        }
      
        .btn-danger {
            background-color: #DC3545;
            border-color: #DC3545;
        }

        .action-cell {
            white-space: nowrap; 
        }
        
        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>


<div class="container my-5">
  <h3 class="d-flex justify-content-between align-items-center">
    Daftar Kategori Menu
    <span class="d-flex gap-2">
        <a href="index.php" class="btn btn-back-custom btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="kategori_form.php" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Tambah Kategori</a>
    </span>
  </h3>

  <div class="table-container">
    <table class="table table-bordered table-striped align-middle shadow-sm">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nama Kategori</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $koneksi->query("SELECT * FROM kategori_menu ORDER BY id_kategori DESC");
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td class="fw-bold"><?= e($row['nama_kategori']) ?></td>
          <td width="160" class="action-cell">
            <a href="kategori_form.php?id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
            <a href="hapus.php?tabel=kategori_menu&id=<?= $row['id_kategori'] ?>" onclick="return confirm('Yakin hapus kategori: <?= e($row['nama_kategori']) ?>?')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
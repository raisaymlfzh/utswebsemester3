<?php
include '../config.php';
$conn = $koneksi; 
include 'header.php';
$page_title = "Tentang Kami";
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
            --color-header-bg: #664208ff;
        }
        body { 
            background-color: var(--color-cream-light); 
            color: var(--color-coffee-dark); 
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        
        .about-section {
            background-color: var(--color-white);
            padding: 50px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 50px;
        }
        
        .about-section h2 {
            color: var(--color-coffee-dark);
            font-weight: 800;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .about-section p {
            line-height: 1.8;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }
        
        .tagline {
            color: var(--color-coffee-medium);
            font-style: italic;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
        }

        .highlight-icon {
            color: #FFC107; 
            font-size: 1.5em;
        }

        .feature-card {
            background-color: var(--color-cream-light);
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            height: 100%;
        }

    </style>
</head>
<body>


<div class="container">
  <div class="about-section">
    
    <h2 class="mb-4">Tentang KopiCa Coffee & Food</h2>
    <p class="tagline">"Tempat di mana aroma kopi bertemu dengan kehangatan makanan."</p>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="feature-card">
                <h4 class="text-center mb-3">Filosofi Kami</h4>
                <p>KopiCa didirikan atas dasar kecintaan kami terhadap biji kopi terbaik dan hidangan yang dibuat dengan hati. Kami percaya bahwa setiap cangkir kopi dan setiap gigitan makanan harus menjadi momen istirahat yang istimewa dari hiruk pikuk kehidupan sehari-hari.</p>
                <p>Kami berkomitmen menyajikan kualitas premium, mulai dari biji kopi arabika pilihan hingga bahan-bahan makanan segar lokal.</p>
            </div>
        </div>
        <div class="col-md-6">
             <div class="feature-card">
                <h4 class="text-center mb-3">Visi dan Misi</h4>
                <p><strong>Visi:</strong> Menjadi kafe pilihan utama di kota yang dikenal karena kualitas kopinya yang konsisten dan suasana yang nyaman dan ramah.</p>
                <p><strong>Misi:</strong></p>
                <ul>
                    <li>Menyediakan menu kopi dan makanan dengan standar kualitas tertinggi.</li>
                    <li>Menciptakan pengalaman pelanggan yang berkesan melalui layanan yang personal.</li>
                    <li>Mendukung petani kopi lokal dan UMKM makanan.</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="text-center pt-3 border-top">
        <p class="text-muted mb-1">
            <i class="fas fa-map-marker-alt highlight-icon me-2"></i> Lokasi: Jl. Limau Manis No. 45, Pusat Kota.
        </p>
        <p class="text-muted">
            <i class="fas fa-phone highlight-icon me-2"></i> Hubungi Kami: (021) 123-4567 | <i class="fas fa-envelope highlight-icon me-2"></i> kopica@gmail.com
        </p>
        
        <a href="index.php" class="btn btn-dark mt-4" style="background-color: var(--color-coffee-dark);">
            <i class="fas fa-home me-2"></i> Kembali ke Beranda
        </a>
    </div>

  </div>
</div>

<?php
include '../public/footer.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
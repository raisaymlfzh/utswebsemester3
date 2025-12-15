<?php
global $koneksi, $conn;

$base_path = '/kopica/'; 

$role = $_SESSION['user']['role'] ?? 'guest'; 

$cart_count = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['qty'] ?? 0; 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KopiCa Coffee & Food</title>
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
        }

        .kopica-dark-bg { 
            background-color: var(--color-header-bg) !important; 
        }
        .header-logo-container {
            display: flex;
            align-items: center;
        }
        .header-logo-icon {
            font-size: 1.5rem;
            margin-right: 10px;
            color: #ffffff;
        }

        .search-input { 
            border-radius: 50px; 
            height: 38px; 
            padding-right: 35px; 
            border: none; 
        }
        .search-btn-icon { 
            position: absolute; 
            right: 10px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: var(--color-coffee-dark); 
        }

        .btn-outline-light {
            border-color: var(--color-white);
            color: var(--color-white);
            transition: all 0.3s;
        }
        .btn-outline-light:hover {
            background-color: var(--color-white);
            color: var(--color-header-bg) !important;
        }
        
    </style>
</head>
<body>

<header class="kopica-dark-bg text-white py-3 shadow-sm sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="mb-0">
            <a href="<?= $base_path ?>public/index.php" class="text-white text-decoration-none header-logo-container">
                 <i class="fas fa-mug-hot header-logo-icon"></i> KOPICA
            </a>
        </h2>
        
        <nav class="d-flex align-items-center">

        <?php if ($role === 'admin'): ?>

            <a href="<?= $base_path ?>admin/kelola_akun.php" 
               class="btn btn-warning btn-sm text-dark fw-bold me-3">
                 <i class="fas fa-user-cog"></i> Kelola Akun
            </a>

            <a href="<?= $base_path ?>public/logout.php" class="btn btn-danger btn-sm">
                 <i class="fas fa-sign-out-alt"></i> Logout
            </a>

        <?php else: ?>


            <a href="<?= $base_path ?>public/menu.php" class="text-white me-3 text-decoration-none">Menu</a>
            <a href="<?= $base_path ?>public/about.php" class="text-white me-3 text-decoration-none">About</a>

            <a href="<?= $base_path ?>public/profil.php" class="text-white me-3 text-decoration-none">
                <i class="fas fa-user"></i> Profil
            </a>

            <a href="<?= $base_path ?>public/orders.php" 
               class="text-white me-3 text-decoration-none">
                 Riwayat Pesanan
            </a>

            <a href="<?= $base_path ?>public/cart.php" 
               class="text-white me-3 text-decoration-none position-relative">
                 <i class="fas fa-shopping-cart"></i>
                 <span id="cart-counter-badge" 
                       class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                       style="display: <?= $cart_count > 0 ? 'inline-block' : 'none'; ?>;">
                     <?= $cart_count ?>
                 </span>
            </a>

            <a href="<?= $base_path ?>public/logout.php" class="btn btn-danger btn-sm">
                 <i class="fas fa-sign-out-alt"></i> Logout
            </a>

        <?php endif; ?>

        </nav>

    </div>
</header>
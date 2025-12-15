<?php
include '../config.php';
$conn = $koneksi; 

$error = '';
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) {

    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($password !== $confirm) {
        $error = "Password tidak cocok!";
    } elseif (strlen($password) < 2) {
        $error = "Password minimal 2 karakter.";
    } else {
       
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - KopiCa</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('https://placehold.co/1920x1080/EAE0D3/795548?text=Coffee+Background');
            background-size: cover;
            background-position: center;
        }

        .register-container {
            background-color: var(--color-white);
            padding: 35px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 90%;
            text-align: center;
            z-index: 10; 
        }

        .register-container h2 {
            color: var(--color-coffee-dark);
            margin-bottom: 25px;
            font-weight: 700;
        }
        
        .register-container .form-control {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: var(--color-cream-light);
        }

        .btn-register {
            width: 100%;
            background-color: var(--color-coffee-medium);
            color: var(--color-white);
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-register:hover {
            background-color: var(--color-coffee-dark);
        }

        .register-container p {
            margin-top: 20px;
        }
        .register-container a {
            color: var(--color-coffee-medium);
            text-decoration: none;
            font-weight: bold;
        }
        .register-container a:hover {
            text-decoration: underline;
        }

        header { 
            display: none !important; 
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2><i class="fas fa-user-plus me-2"></i> Buat Akun Baru</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form action="register.php" method="post">
        
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" value="<?= e($nama) ?>" required>
        
        <input type="text" name="username" class="form-control" placeholder="Username" value="<?= e($username) ?>" required>
        
        <input type="email" name="email" class="form-control" placeholder="Email (Opsional)" value="<?= e($email) ?>">
        
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        
        <input type="password" name="confirm" class="form-control" placeholder="Konfirmasi Password" required>
        
        <button type="submit" class="btn-register mt-3">Daftar</button>
    </form>
    
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
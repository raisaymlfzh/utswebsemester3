<?php
include '../config.php';
$conn = $koneksi; 

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_baru = trim($_POST['nama'] ?? '');
    $email_baru = trim($_POST['email'] ?? '');
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_baru = $_POST['konfirmasi_baru'] ?? '';
    
    if (empty($nama_baru) || empty($email_baru)) {
        $error = "Nama dan Email harus diisi.";
    } elseif (!empty($password_baru) && $password_baru !== $konfirmasi_baru) {
        $error = "Password baru dan konfirmasi tidak cocok!";
    } else {

        $update_fields = ['nama' => $nama_baru, 'email' => $email_baru];
        $bind_types = 'ss';
        $bind_params = [&$nama_baru, &$email_baru];
      
        if (!empty($password_baru)) {
            $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);
            $update_fields['password_hash'] = $hash_baru;
            $bind_types .= 's';
            $bind_params[] = &$hash_baru;
        }

        $set_clause = [];
        foreach (array_keys($update_fields) as $field) {
            $set_clause[] = "$field = ?";
        }
        
        $query = "UPDATE users SET " . implode(', ', $set_clause) . " WHERE id_user = ?";
 
        $bind_types .= 'i';
        $bind_params[] = &$id_user;
  
        $stmt = $conn->prepare($query);
        
        array_unshift($bind_params, $bind_types);
        call_user_func_array([$stmt, 'bind_param'], $bind_params);
        
        if ($stmt->execute()) {
            $success = "Profil berhasil diperbarui!";
            $_SESSION['user']['nama'] = $nama_baru;
            $_SESSION['user']['username'] = $_POST['username'] ?? $_SESSION['user']['username']; 
            
        } else {
            $error = "Gagal memperbarui profil: " . $conn->error;
        }
        $stmt->close();
    }
}

$stmt_get = $conn->prepare("SELECT id_user, username, nama, email, role FROM users WHERE id_user = ?");
$stmt_get->bind_param("i", $id_user);
$stmt_get->execute();
$profil = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();

if (!$profil) {
    unset($_SESSION['user']);
    header('Location: login.php');
    exit;
}

$page_title = "Profil Pengguna";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> KopiCa</title>
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
            min-height: 100vh;
        }
        .profile-card {
            max-width: 600px;
            margin: 50px auto;
            background-color: var(--color-white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
        
        .btn-secondary-custom {
             background-color: #f0ebe8; 
             border-color: #ddd;
             color: var(--color-coffee-dark);
             transition: background-color 0.3s; 
        }
        .btn-secondary-custom:hover {
            background-color: #ccc;
        }
        
        .role-badge {
            font-size: 0.9rem;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 50px;
            background-color: var(--color-coffee-medium);
            color: var(--color-white);
        }
    </style>
</head>
<body>

<div class="container">
  <div class="profile-card">
    <h3><i class="fas fa-user-circle me-2"></i> Detail Profil Anda</h3>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    
    <div class="mb-4 text-center">
        <p class="h5 mb-1"><?= e($profil['username']) ?></p>
        <span class="role-badge"><?= ucfirst($profil['role']) ?></span>
    </div>

    <form method="post">

      <div class="mb-3">
        <label for="nama" class="form-label fw-bold">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" value="<?= e($profil['nama']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label fw-bold">Email</label>
        <input type="email" name="email" id="email" value="<?= e($profil['email']) ?>" class="form-control" required>
      </div>

      <hr class="my-4">
      <h5 class="mb-3 text-muted"><i class="fas fa-lock me-2"></i> Ganti Password (Opsional)</h5>

      <div class="mb-3">
        <label for="password_baru" class="form-label">Password Baru</label>
        <input type="password" name="password_baru" id="password_baru" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
      </div>

      <div class="mb-3">
        <label for="konfirmasi_baru" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="konfirmasi_baru" id="konfirmasi_baru" class="form-control">
      </div>
      
      <div class="d-flex justify-content-between mt-4">
        <a href="index.php" class="btn btn-secondary-custom">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
        
        <button type="submit" class="btn btn-submit text-white">
            <i class="fas fa-sync-alt"></i> Update Profil
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
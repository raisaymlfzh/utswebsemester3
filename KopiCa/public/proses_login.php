<?php
include '../config.php';
$conn = $koneksi; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $error = '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi.";
    } else {

        $stmt = $conn->prepare("SELECT id_user, username, password_hash, nama, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $stored_hash = $user['password_hash'];

            $is_password_valid = password_verify($password, $stored_hash);

            if (!$is_password_valid) {
                 $is_password_valid = ($password === $stored_hash);
            }


            if ($is_password_valid) {

                $_SESSION['user'] = [
                    'id_user' => $user['id_user'],
                    'username' => $user['username'],
                    'nama' => $user['nama'],
                    'role' => $user['role'] 
                ];

                if ($user['role'] === 'admin') {
                    header('Location: ../admin/index.php'); 
                } else {
                    header('Location: menu.php'); 
                }
                exit; 
                
            } else {
                $error = "Password salah. (Status: Gagal verifikasi)";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }

        $stmt->close();
    }

    $_SESSION['login_error'] = $error;
    header('Location: login.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>
<?php
session_start();
include "../config.php"; 
$conn = $koneksi; 

function alert_and_redirect($message, $location) {
    echo "<script>alert('$message'); window.location='$location';</script>";
    exit;
}

if (empty($_SESSION['cart'])) {
    alert_and_redirect('Keranjang Anda kosong!', 'menu.php');
}

if (!isset($_SESSION['user']['id_user'])) {
    alert_and_redirect('Silahkan login terlebih dahulu', 'login.php');
}

$id_user = $_SESSION['user']['id_user'];
$cart_data = $_SESSION['cart'];

$total_harga = 0;
foreach ($cart_data as $item) {
    $total_harga += ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metode_pembayaran'])) {
    $metode_pembayaran = $_POST['metode_pembayaran']; 

    if ($metode_pembayaran === 'Cash') {
        $status_pesanan = "Selesai"; 
    } else {
        $status_pesanan = "Pending";
    }

    if (!in_array($metode_pembayaran, ['Cash', 'Qris'])) {
        alert_and_redirect('Metode pembayaran tidak valid!', 'checkout.php');
    }

    $query_pesanan = "INSERT INTO pesanan (id_user, total_harga, status, metode_pembayaran, tanggal_pesan) 
                      VALUES ('$id_user', '$total_harga', '$status_pesanan', '$metode_pembayaran', NOW())";

    if (!mysqli_query($conn, $query_pesanan)) {
        die("Gagal membuat pesanan utama: " . mysqli_error($conn));
    }

    $id_pesanan_baru = mysqli_insert_id($conn);

    $query_detail_parts = [];
    foreach ($cart_data as $item) {
        $id_menu = mysqli_real_escape_string($conn, $item['id_menu'] ?? 0);
        $qty = (int)($item['qty'] ?? 0);
        $harga_satuan = (float)($item['harga'] ?? 0);
        $subtotal = $qty * $harga_satuan;

        if ($qty > 0) {
            $query_detail_parts[] = "('$id_pesanan_baru', '$id_menu', '$qty', '$harga_satuan', '$subtotal')";
        }
    }

    if (!empty($query_detail_parts)) {
        $query_detail = "INSERT INTO detail_pesanan (id_pesanan, id_menu, qty, harga_satuan, subtotal) VALUES " . implode(", ", $query_detail_parts);
        
        if (!mysqli_query($conn, $query_detail)) {
            die("Gagal menyimpan detail pesanan: " . mysqli_error($conn));
        }
    }

    unset($_SESSION['cart']);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Checkout Berhasil</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .qr-code-img {
                max-width: 200px;
                margin: 10px auto;
                border: 1px solid #ddd;
                padding: 5px;
                display: block;
            }
        </style>
    </head>
    <body>
    <script>
        const metode = "<?= $metode_pembayaran ?>";
        const total = "Rp <?= number_format($total_harga, 0, ',', '.') ?>";
        const orderId = "<?= $id_pesanan_baru ?>";
        
        let htmlContent = '';
        let confirmButtonText = 'Selesai';
        let title = "Pesanan Berhasil Dibuat!";

        if (metode === 'Qris') {
            title = "⏳ Pembayaran Menunggu (QRIS)";
            
            const qrisImageUrl = '../public/qris_static.jpg'; 
            
            htmlContent = `
                <p class="text-start">Pesanan Anda telah dibuat. Selesaikan pembayaran sebesar ${total} melalui QRIS di bawah ini.</p>
                <div class="alert alert-warning p-2 small">
                    Status pesanan saat ini Pending. Admin akan memverifikasi pembayaran Anda secara manual.
                </div>
                <img src="${qrisImageUrl}" alt="QRIS Code" class="qr-code-img">
                <p class="small text-muted mt-2">Pastikan jumlah transfer adalah ${total}.</p>
            `;
            confirmButtonText = 'Kembali ke Menu';

        } else { 
            htmlContent = `
                <p class="text-start">Pesanan Anda berhasil dibuat.</p>
                <p class="text-start">Total pembayaran: ${total}</p>
                <div class="alert alert-success p-2 small">
                    Pesanan Anda sedang disiapkan. Silahkan bayar tunai kepada kasir ketika Anda mengambil pesanan.
                </div>
            `;
        }

        Swal.fire({
            title: title,
            html: htmlContent,
            icon: "success",
            confirmButtonText: confirmButtonText,
            allowOutsideClick: false,
        }).then(() => {
            window.location.href = "menu.php";
        });
    </script>
    </body>
    </html>
    <?php
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f5f0; }
        .checkout-container { max-width: 600px; margin-top: 50px; }
        .card-summary { border: 1px solid #795548; border-radius: 10px; }
        .kopica-text { color: #4A2C2A; }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="text-center kopica-text mb-4"><i class="fas fa-credit-card me-2"></i> Konfirmasi Pembayaran</h2>
    
    <div class="card p-4 shadow-lg card-summary">
        <h4 class="card-title kopica-text">Ringkasan Pesanan</h4>
        <hr>
        <ul class="list-group list-group-flush mb-3">
            <?php 
            $subtotal_items = 0;
            foreach ($cart_data as $item): 
                $subtotal_item = ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
                $subtotal_items += $subtotal_item;
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($item['nama_menu'] ?? 'Item') ?> (x<?= $item['qty'] ?? 1 ?>)
                <span class="fw-bold">Rp <?= number_format($subtotal_item, 0, ',', '.') ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="d-flex justify-content-between fw-bold fs-5 mt-3 pt-2 border-top">
            <span>Total Harga:</span>
            <span class="kopica-text">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
        </div>

        <h4 class="mt-4 kopica-text">Pilih Metode Pembayaran</h4>
        
        <form method="POST">
            
            <div class="form-check p-3 mb-2 border rounded">
                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeCash" value="Cash" required>
                <label class="form-check-label fw-bold" for="metodeCash">
                    <i class="fas fa-money-bill-wave me-2"></i> Cash (Bayar di tempat)
                </label>
            </div>
            
            <div class="form-check p-3 mb-3 border rounded">
                <input class="form-check-input" type="radio" name="metode_pembayaran" id="metodeQris" value="Qris" required>
                <label class="form-check-label fw-bold" for="metodeQris">
                    <i class="fas fa-qrcode me-2"></i> QRIS (Scan untuk Bayar)
                </label>
                <small class="text-muted d-block mt-1">Status pesanan akan Pending menunggu konfirmasi Admin.</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2" style="background-color: #795548; border-color: #795548;">
                <i class="fas fa-check-circle me-2"></i> Konfirmasi & Bayar
            </button>
        </form>
    </div>
    
    <div class="text-center mt-3">
        <a href="cart.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali ke Keranjang</a>
    </div>

</div>

</body>
</html>
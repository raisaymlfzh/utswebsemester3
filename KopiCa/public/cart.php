<?php
session_start();
include '../config.php'; 
$conn = $koneksi;

function get_cart_item_count() {
    $total = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) {
        $total += $item['qty'] ?? 0;
    }
    return $total;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (isset($_GET['add'])) {
    $id_menu = mysqli_real_escape_string($conn, $_GET['add']);

    $query = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu='$id_menu' LIMIT 1");
    if (!$query) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Query Error', 'count' => get_cart_item_count()]);
            exit;
        }
        die("Query Error: " . mysqli_error($conn));
    }

    $menu = mysqli_fetch_assoc($query);
    $response = ['status' => 'error', 'count' => get_cart_item_count()]; 

    if ($menu) {
        if (isset($_SESSION['cart'][$id_menu])) {
            $_SESSION['cart'][$id_menu]['qty'] += 1;
        } else {
            $_SESSION['cart'][$id_menu] = [
                'id_menu' => $menu['id_menu'],
                'nama_menu' => $menu['nama_menu'],
                'harga' => $menu['harga'],
                'qty' => 1
            ];
        }

        $response['status'] = 'success';
        $response['count'] = get_cart_item_count();
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit; 
        } else {
            header("Location: menu.php");
            exit;
        }
    }

    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Menu tidak ditemukan.', 'count' => get_cart_item_count()]);
        exit;
    }
}

if (isset($_GET['remove'])) {
    $id_menu = $_GET['remove'];
    unset($_SESSION['cart'][$id_menu]);
    header("Location: cart.php");
    exit;
}

if (isset($_POST['update_qty'])) {
    if (!empty($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $id => $qty) {
            $qty = max(1, intval($qty));
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] = $qty;
            }
        }
    }
    header("Location: cart.php");
    exit;
}

include '../public/header.php';
?>

<div class="container py-5">
    <h2 class="kopica-text fw-bold mb-4">Keranjang Belanja</h2>

    <div class="mt-3">
            <a href="menu.php" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning">Keranjang kosong.</div>
        <a href="menu.php" class="btn btn-sm btn-secondary mt-2">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Menu
        </a>
    <?php else: ?>

        <form method="post">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th width="120">Qty</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php 
                    $grand_total = 0;
                    foreach ($_SESSION['cart'] as $item): 
                        if (!isset($item['nama_menu'], $item['harga'], $item['qty'])) continue; 

                        $total = $item['harga'] * $item['qty'];
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>
                            <input type="number" 
                                             name="qty[<?= htmlspecialchars($item['id_menu']) ?>]" 
                                             value="<?= htmlspecialchars($item['qty']) ?>" 
                                             class="form-control"
                                             min="1">
                        </td>
                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                        <td>
                            <a href="cart.php?remove=<?= htmlspecialchars($item['id_menu']) ?>" 
                               class="btn btn-danger btn-sm">
                                 Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Grand Total</th>
                        <th colspan="2">Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>

            </table>
            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <button type="submit" name="update_qty" class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Update Qty
                </button>
            </div>
            
        </form>
        
        <a href="checkout.php" class="btn btn-success mt-2">
            <i class="fas fa-money-check-alt me-1"></i> Checkout
        </a>
        



    <?php endif; ?>
</div>

<?php include '../public/footer.php'; ?>
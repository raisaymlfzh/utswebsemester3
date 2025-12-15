<?php 

include '../config.php'; 
$conn = $koneksi; 

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
$query_kategori = "SELECT * FROM kategori_menu ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $query_kategori); 
$kategori_list = [];

if ($result_kategori) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori_list[] = $row;
    }
}

$has_kategori = count($kategori_list) > 0;
?>

<?php
include '../public/header.php'; 
?>

<style>
:root {
    --color-header: #4A2C2A; 
    --color-button-primary: #795548; 
    --color-bg-light: #F8F5F2; 
    --color-text-main: #4A2C2A; 
    --color-white: #ffffff;
    --color-kopica-text: #4A2C2A; 
}

.kopica-text { color: var(--color-kopica-text) !important; }

.section-padding {
    padding: 60px 0;
}

.kategori-card {
    background-color: var(--color-white);
    border-radius: 10px;
    border: 1px solid #ddd;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s;
    text-align: center;
    padding: 30px 20px;
    min-height: 180px;
}

.kategori-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}

.kategori-card i {
    font-size: 36px;
    color: var(--color-button-primary);
    margin-bottom: 10px;
}

.kategori-card h4 {
    color: var(--color-kopica-text);
    font-weight: bold;
    margin-bottom: 15px;
}

.btn-view-menu {
    background-color: var(--color-button-primary);
    color: var(--color-white);
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn-view-menu:hover {
    background-color: #5d4037;
    color: var(--color-white);
}

</style>


<div class="section-padding" style="background-color: var(--color-bg-light);">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold kopica-text display-4">Jelajahi Kategori Menu</h1>
            <p class="lead text-muted">Temukan kopi dan makanan favorit Anda berdasarkan kategori.</p>
        </div>

        <div class="row justify-content-center">
            <?php if ($has_kategori): ?>
                <?php foreach($kategori_list as $kategori): ?>
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="kategori-card">
                            <i class="fas fa-tags"></i> 
                            <h4><?= e($kategori['nama_kategori'] ?? 'Kategori Baru') ?></h4>
                            
                            <a href="menu.php?kategori=<?= e($kategori['id_kategori']) ?>" class="btn btn-view-menu">
                                Lihat Menu
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center shadow-sm">
                        <i class="fas fa-exclamation-triangle me-2"></i> Belum ada kategori menu yang tersedia saat ini.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include '../public/footer.php'; 
?>
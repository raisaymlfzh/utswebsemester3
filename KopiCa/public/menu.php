<?php 

include '../config.php'; 
$conn = $koneksi; 

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

$filter_kategori_id = $_GET['kategori'] ?? null;
$search = $_GET['q'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'm.nama_menu'; 
$sort_order = $_GET['sort_order'] ?? 'ASC'; 

$query_where = ["m.stok > 0"]; 
$halaman_title = "Daftar Menu";

if (!empty($filter_kategori_id)) {
    $kategori_ids_to_filter = is_array($filter_kategori_id) ? $filter_kategori_id : [$filter_kategori_id];
    
    $safe_kategori_ids = array_map(function($id) use ($conn) {
        $id = (int) $id; 
        return "'" . mysqli_real_escape_string($conn, $id) . "'";
    }, $kategori_ids_to_filter);
    
    if (!empty($safe_kategori_ids)) {
        $query_where[] = "m.id_kategori IN (" . implode(",", $safe_kategori_ids) . ")";
        
        $res_nama = mysqli_query($conn, "SELECT nama_kategori FROM kategori_menu WHERE id_kategori IN (" . implode(",", $safe_kategori_ids) . ")");
        $kategori_names = [];
        while ($nama_row = mysqli_fetch_assoc($res_nama)) {
            $kategori_names[] = e($nama_row['nama_kategori']);
        }
        $halaman_title = "Menu Kategori: " . implode(", ", $kategori_names);
    }
}

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $query_where[] = "(m.nama_menu LIKE '%{$safe_search}%' OR k.nama_kategori LIKE '%{$safe_search}%')";

    if (empty($filter_kategori_id)) {
        $halaman_title = "Hasil Pencarian untuk: " . e($search);
    } else {
        $halaman_title = e($halaman_title) . " (Cari: " . e($search) . ")";
    }
}

$allowed_sort_by = ['m.harga', 'm.nama_menu'];
$safe_sort_by = in_array($sort_by, $allowed_sort_by) ? $sort_by : 'm.nama_menu';
$safe_sort_order = (strtoupper($sort_order) === 'DESC') ? 'DESC' : 'ASC';

$query_where_string = "WHERE " . implode(" AND ", $query_where);

$query = "SELECT m.*, k.nama_kategori 
          FROM menu m 
          JOIN kategori_menu k ON m.id_kategori = k.id_kategori
          {$query_where_string} 
          ORDER BY {$safe_sort_by} {$safe_sort_order}";

$result = mysqli_query($conn, $query); 

if (!$result && !$is_ajax) {
    die("Query Gagal: " . mysqli_error($conn) . " | Cek kolom 'nama_kategori' di tabel 'kategori_menu'.");
}

$menu_items = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menu_items[] = $row;
    }
}

$has_results = count($menu_items) > 0; 


$query_kategori = "SELECT id_kategori, nama_kategori FROM kategori_menu ORDER BY nama_kategori";
$result_kategori = mysqli_query($conn, $query_kategori); 
$all_kategori = [];
if ($result_kategori) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $all_kategori[] = $row;
    }
}
?>

<?php
if (!$is_ajax) {
    include '../public/header.php'; 
?>

<style>
:root {
    --color-header: #4A2C2A; 
    --color-button-primary: #795548; 
    --color-bg-light: #F8F5F2; 
    --color-text-main: #4A2C2A; 
    --color-text-header: #F8F5F2; 
    --color-kopica-text: #4A2C2A; 
    --color-coffee-medium: #795548; 
    

    --color-cream-bg: #F5E5C9;
    --color-cream-border: #E8D7B9;     

    --color-light-green: #a7d97b; 
    --color-light-green-hover: #8fc763; 
    --color-logout-btn: #DC3545; 
    --color-white: #ffffff;
}

.kopica-text { color: var(--color-kopica-text) !important; }
.hero-section {
    background-color: var(--color-white); 
    padding: 60px 0 20px 0; 
    border-bottom: 1px solid #eee;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.menu-card {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
    background-color: var(--color-white);
}

.menu-card:hover { transform: translateY(-5px); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12); }
.menu-card-img { height: 200px; object-fit: cover; border-top-left-radius: 10px; border-top-right-radius: 10px; width: 100%; }
.menu-card .card-body { padding: 15px; }

.menu-card .badge-kategori { 
    position: absolute; top: 15px; left: 15px; 
    background-color: var(--color-light-green); color: var(--color-kopica-text); 
    padding: 5px 12px; border-radius: 50rem; font-weight: bold; font-size: 0.85rem; 
    box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 10; 
}

.btn-add-cart { 
    background-color: var(--color-light-green); color: var(--color-kopica-text); 
    border: 1px solid var(--color-light-green); border-radius: 50rem !important; 
    padding: 8px 15px; font-weight: bold; transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease; 
}

.btn-add-cart:hover { 
    background-color: var(--color-light-green-hover); color: var(--color-kopica-text); 
    border-color: var(--color-light-green-hover); 
}

.badge.bg-danger { 
    background-color: #dc3545 !important; padding: 8px 15px; border-radius: 50rem; 
}

.hero-section .form-control { border-color: #ddd; padding: 10px 20px; }
.btn-search { background-color: var(--color-button-primary); border-radius: 50rem !important; padding: 10px 25px; transition: background-color 0.3s ease; }
.btn-search:hover { background-color: #a0522d; }

.category-filter-section {
    background-color: var(--color-bg-light);
    border-radius: 10px;
    padding: 20px;
    margin-top: 30px;
}

.filter-input-hidden {
    display: none; 
}

.category-card-label {
    background-color: var(--color-cream-bg); 
    border: 1px solid var(--color-cream-border); 
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px; 
    text-decoration: none; 
    color: var(--color-kopica-text); 
    min-height: 85px; 
    cursor: pointer;
    height: 100%;
    position: relative; 
}

.category-card-label:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.filter-input-hidden:checked + .category-card-label {
    background-color: var(--color-white); 
    border-color: var(--color-coffee-medium); 
    color: var(--color-kopica-text);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.category-card-icon {
    font-size: 1.8rem; 
    color: var(--color-button-primary); 
    margin-bottom: 3px; 
}

.filter-input-hidden:checked + .category-card-label .category-card-icon {
    color: var(--color-button-primary); 
}

.category-card-text {
    font-weight: bold;
    font-size: 0.9rem; 
    text-align: center;
}

.category-card-label::after {
    content: "\2713"; 
    position: absolute;
    top: -8px; 
    right: -8px;
    width: 18px; 
    height: 18px; 
    line-height: 18px;
    text-align: center;
    border-radius: 50%;

    background-color: var(--color-coffee-medium); 
    color: var(--color-white); 
    border: 1px solid var(--color-white); 
    font-size: 0.7rem; 
    font-weight: bold;
    opacity: 0; 
    transition: opacity 0.2s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2); 
}

.filter-input-hidden:checked + .category-card-label::after {
    opacity: 1;
    background-color: var(--color-cream-bg); 
    color: var(--color-kopica-text); 
    border: 1px solid var(--color-kopica-text); 
}

.radio-sort-label {
    border: 1px solid var(--color-coffee-medium);
    color: var(--color-coffee-medium);
    background-color: var(--color-white);
    padding: 5px 12px;
    border-radius: 50rem;
    cursor: pointer;
    transition: background-color 0.2s, color 0.2s;
    font-size: 0.9rem;
    margin-right: 8px;
}

.radio-sort-input {
    display: none;
}

.radio-sort-input:checked + .radio-sort-label {
    background-color: var(--color-coffee-medium);
    color: var(--color-white);
}

#menu-loading {
    display: none;
    text-align: center;
    padding: 30px;
}

.toast-container {
    z-index: 1050; 
}
#cart-toast {
    background-color: var(--color-coffee-medium) !important; 
    color: var(--color-white) !important; 
    border: 3px solid var(--color-cream-bg); 
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4); 
    font-weight: bold;
    border-radius: 10px;
}
.toast.show {
    opacity: 1;
    transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
    transform: translateY(-10px); 
}
</style>


<section class="hero-section">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold kopica-text display-4">Katalog Menu KopiCa</h1>
            <p class="lead text-muted">Nikmati Secangkir Kopi & Cemilan Lezat | Temukan menu favorit Anda!</p>
            
            <form class="mt-4" method="get" action="menu.php" id="main-filter-form">
                <div class="d-none">
                    <input type="hidden" name="sort_by" value="<?= e($safe_sort_by) ?>" id="hidden_sort_by">
                    <input type="hidden" name="sort_order" value="<?= e($safe_sort_order) ?>" id="hidden_sort_order">
                    <input type="hidden" name="current_sort" value="<?= e("{$safe_sort_by}_{$safe_sort_order}") ?>">
                </div>
                
                <div class="d-flex justify-content-center">
                    <input type="text" name="q" value="<?= e($search) ?>" id="search-input" class="form-control w-75 me-2 rounded-pill shadow-sm" placeholder="Cari menu atau kategori...">
                </div>
                

                <div class="category-filter-section shadow-sm mt-4">
                    <p class="mb-3 fw-bold kopica-text">Filter Berdasarkan Kategori (Pilih Multiple):</p>
                    <div class="row justify-content-center g-3">
                        <?php 
                        $kategori_icons = [
                            '1' => 'fas fa-coffee', '3' => 'fas fa-utensils', '6' => 'fas fa-cookie-bite', '7' => 'fas fa-cake-candles', 
                        ];
                        ?>
                        <?php foreach($all_kategori as $kategori): ?>
                            <?php 
                                   $is_checked = (is_array($filter_kategori_id) && in_array($kategori['id_kategori'], $filter_kategori_id)) || 
                                                 (!is_array($filter_kategori_id) && $filter_kategori_id == $kategori['id_kategori']);
                                   $icon_class = $kategori_icons[$kategori['id_kategori']] ?? 'fas fa-tag';
                            ?>
                            <div class="col-6 col-md-3 col-lg-2">
                                <input 
                                    type="checkbox" 
                                    id="kategori_<?= e($kategori['id_kategori']) ?>" 
                                    name="kategori[]" 
                                    value="<?= e($kategori['id_kategori']) ?>" 
                                    class="filter-input-hidden" 
                                    <?= $is_checked ? 'checked' : '' ?> 
                                    onchange="applyFilters()" 
                                >
                                <label for="kategori_<?= e($kategori['id_kategori']) ?>" class="category-card-label">
                                    <i class="<?= e($icon_class) ?> category-card-icon"></i>
                                    <span class="category-card-text"><?= e($kategori['nama_kategori']) ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                


            </form>
        </div>
    </div>
</section>

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="cart-toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body" id="toast-message">
                Menu berhasil ditambahkan ke keranjang!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class="container py-5">
    <div id="menu-results-container">
<?php
} 
?>

    <div id="menu-loading" style="<?= $is_ajax ? 'display:none;' : '' ?>">
        <i class="fas fa-spinner fa-spin fa-2x kopica-text"></i>
        <p class="kopica-text mt-2">Memuat menu...</p>
    </div>

    <h2 class="kopica-text fw-bold mb-4 border-bottom pb-2" id="halaman-title"><?= e($halaman_title) ?></h2>
    
    <div class="row" id="menu-list">
        
        <?php if ($has_results): ?>
            <?php foreach($menu_items as $row): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card menu-card h-100">
                        <a href="#" onclick="event.preventDefault(); document.getElementById('kategori_<?= e($row['id_kategori']) ?>').checked = true; applyFilters(); return false;" class="badge badge-kategori text-decoration-none">
                             <?= e($row['nama_kategori'] ?? 'Kategori') ?>
                        </a>

                        <img src="<?= e($row['gambar'] ?? 'https://placehold.co/400x250/A1887F/ffffff?text=KOPICA+MENU') ?>" 
                            class="card-img-top menu-card-img" 
                            alt="<?= e($row['nama_menu']) ?>"
                            onerror="this.onerror=null; this.src='https://placehold.co/400x250/A1887F/ffffff?text=KOPICA+MENU'">
                            
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate kopica-text fw-bold"><?= e($row['nama_menu']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?= e(substr($row['deskripsi'] ?? 'Deskripsi belum tersedia.', 0, 90)) ?><?= (strlen($row['deskripsi'] ?? '') > 90) ? '...' : '' ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <span class="fw-bold fs-5" style="color: var(--color-coffee-medium);">Rp <?= number_format($row['harga'],0,',','.') ?></span>
                                
                                <?php if ($row['stok'] > 0): ?>
                                    <button 
                                        type="button" 
                                        data-id="<?= e($row['id_menu']) ?>" 
                                        class="btn btn-add-cart btn-sm add-to-cart" 
                                        onclick="addToCart(this)">
                                        <i class="fas fa-cart-plus me-1"></i> Tambah
                                    </button>

                                <?php else: ?>
                                    <span class="badge bg-danger">Stok Habis</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center shadow-sm">
                    <i class="fas fa-exclamation-triangle me-2"></i> Menu tidak ditemukan berdasarkan filter dan pencarian Anda.
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
if (!$is_ajax) {
?>
    </div>
</div>

<a href="#" class="scroll-to-top">↑</a>

<script>
    const form = document.getElementById('main-filter-form');
    const menuList = document.getElementById('menu-list');
    const titleElement = document.getElementById('halaman-title');
    const loadingIndicator = document.getElementById('menu-loading');
    const searchInput = document.getElementById('search-input'); 

    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    function applyFilters() {
        loadingIndicator.style.display = 'block';
        menuList.innerHTML = ''; 
        titleElement.innerHTML = ''; 

        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        
        const url = 'menu.php?' + params;

        history.pushState(null, '', url);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                loadingIndicator.style.display = 'none';

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newMenuList = doc.getElementById('menu-list').innerHTML;
                const newTitle = doc.getElementById('halaman-title').innerHTML;

                menuList.innerHTML = newMenuList;
                titleElement.innerHTML = newTitle;
            })
            .catch(error => {
                loadingIndicator.style.display = 'none';
                menuList.innerHTML = `<div class="col-12"><div class="alert alert-danger text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Terjadi kesalahan saat memuat menu. Silakan coba lagi.
                                    </div></div>`;
                console.error('Error fetching menu data:', error);
            });
    }

    function toggleSort(clickedValue, form) {
        const currentSortField = form.elements['current_sort'];
        const hiddenSortBy = form.elements['hidden_sort_by'];
        const hiddenSortOrder = form.elements['hidden_sort_order'];
        
        const [sortBy, sortOrder] = clickedValue.split('_');

        hiddenSortBy.value = sortBy;
        hiddenSortOrder.value = sortOrder;
        currentSortField.value = sortBy + '_' + sortOrder;

        applyFilters();
    }

    function addToCart(buttonElement) {
        const id_menu = buttonElement.getAttribute('data-id');
        const toastElement = document.getElementById('cart-toast');
        const toastMessage = document.getElementById('toast-message');
        const cartCounterBadge = document.getElementById('cart-counter-badge'); 

        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Proses...';

        fetch(`cart.php?add=${id_menu}`, { 
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal menambah cart. Status HTTP: ' + response.status);
            }
            return response.json(); 
        })
        .then(data => {
            if (data.status === 'success') {
                toastMessage.textContent = 'Menu berhasil ditambahkan ke keranjang!';
                toastElement.className = toastElement.className.replace(/bg-danger|bg-warning/, 'bg-success'); 

                if (cartCounterBadge && data.count !== undefined) {
                    cartCounterBadge.textContent = data.count; 
                    cartCounterBadge.style.display = (data.count > 0) ? 'inline-block' : 'none'; 
                }

            } else {
                toastMessage.textContent = 'Gagal menambahkan menu. Pesan: ' + (data.message || 'Unknown error');
                toastElement.className = toastElement.className.replace(/bg-success|bg-warning/, 'bg-danger');
            }

            const toast = new bootstrap.Toast(toastElement); 
            toast.show();
        })
        .catch(error => {
            toastMessage.textContent = 'Terjadi kesalahan: ' + error.message;
            toastElement.className = toastElement.className.replace(/bg-success|bg-warning/, 'bg-danger');
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            console.error('Error adding to cart:', error);
        })
        .finally(() => {
            buttonElement.disabled = false;
            buttonElement.innerHTML = '<i class="fas fa-cart-plus me-1"></i> Tambah';
        });
    }

    const debouncedApplyFilters = debounce(applyFilters, 300);
    searchInput.addEventListener('input', debouncedApplyFilters);

</script>

<?php
include '../public/footer.php'; 
} 
?>
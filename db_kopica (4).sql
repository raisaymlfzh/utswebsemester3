-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Des 2025 pada 05.25
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kopica`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) GENERATED ALWAYS AS (`qty` * `harga_satuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `qty`, `harga_satuan`) VALUES
(1, 1, 2, 1, 8000.00),
(2, 2, 6, 1, 35000.00),
(3, 5, 6, 1, 35000.00),
(4, 6, 6, 1, 35000.00),
(5, 7, 6, 1, 35000.00),
(6, 8, 6, 1, 35000.00),
(7, 9, 6, 1, 35000.00),
(8, 10, 1, 1, 25000.00),
(9, 11, 1, 1, 25000.00),
(10, 12, 6, 2, 35000.00),
(11, 12, 9, 1, 20000.00),
(12, 13, 1, 1, 25000.00),
(13, 14, 5, 2, 15000.00),
(14, 15, 6, 2, 35000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_menu`
--

CREATE TABLE `kategori_menu` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_menu`
--

INSERT INTO `kategori_menu` (`id_kategori`, `nama_kategori`) VALUES
(1, 'coffe'),
(3, 'food'),
(6, 'cemilan'),
(7, 'chocolate');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `id_kategori` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `harga`, `stok`, `id_kategori`, `gambar`, `deskripsi`, `created_at`) VALUES
(1, 'americano', 25000.00, 15, 1, 'https://cdn.rri.co.id/berita/Kendari/o/1729660839165-americano_b74a8154-454b-4f74-9a6c-95fbc4152ed3/67sqf0h03mpqcx9.webp', 'americano adalah kopi pait se pait kehidupan', '2025-11-25 11:01:06'),
(2, 'nasi goreng', 8000.00, 1, 3, 'https://static.promediateknologi.id/crop/0x0:0x0/0x0/webp/photo/p2/76/2025/08/27/WhatsApp-Image-2025-08-27-at-093314-1440455206.jpeg', 'nasi goreng dengan taburan emas', '2025-11-25 11:22:56'),
(5, 'Mie Goreng', 15000.00, 12, 3, 'https://static.promediateknologi.id/crop/0x0:0x0/750x500/webp/photo/p1/995/2024/06/05/mie-goreng-6715015_1280-3474983567.jpg', 'mi instan goreng instan ikonik dan populer asal Indonesia yang disukai secara global', '2025-11-28 03:19:02'),
(6, 'Cappucino', 35000.00, 23, 1, 'https://cdn.rri.co.id/berita/Meulaboh/o/1748144221077-photo-hot-cappuccino_900368-116/yxwpo0vytjdl46d.jpeg', 'Minuman klasik dengan perpaduan sempurna antara espresso yang kaya rasa dan susu hangat dengan lapisan busa lembut di atasnya.', '2025-11-28 03:19:41'),
(7, 'Iced Latte', 20000.00, 10, 1, 'https://d1r9hss9q19p18.cloudfront.net/uploads/2016/07/es-kopi-latte.jpg', 'minuman kopi dingin yang dibuat dengan mencampurkan espresso atau kopi pekat dengan susu dingin dan es batu', '2025-11-28 03:22:35'),
(8, 'Mie Rebus', 20000.00, 0, 3, 'https://cdn0-production-images-kly.akamaized.net/26BnHYilY3L2NRFyoKTn-pBFe5c=/0x373:667x749/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/4408744/original/012982500_1682652511-shutterstock_2185518103.jpg', 'Mie Rebus Sultandengan Kuah Kental Gurih, Topping Melimpah Ruah! Sekali coba, langsung ketagihan', '2025-11-28 03:22:54'),
(9, 'Chocolate', 20000.00, 23, 7, 'https://putrapanganprima.com/wp-content/uploads/2024/08/ezgif-7-ae7ee6db81.webp', '', '2025-11-28 03:23:15'),
(10, 'White Chocolate', 20000.00, 43, 7, 'https://img-global.cpcdn.com/steps/5a253b4645a85976/400x400cq80/photo.jpg', '', '2025-11-28 03:23:41'),
(11, 'Mie Bangladesh', 35000.00, 43, 3, 'https://palpos.disway.id/upload/6750242e0385e99df6b1f787db2ddfe0.jpg', 'hidangan mi instan khas Indonesia yang berasal dari Medan, Sumatera Utara', '2025-11-28 03:24:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `total_harga` decimal(14,2) NOT NULL,
  `status` enum('pending','diproses','selesai','dibatalkan') DEFAULT 'pending',
  `tanggal_pesan` datetime DEFAULT current_timestamp(),
  `alamat` text DEFAULT NULL,
  `metode_pembayaran` enum('Cash','Qris') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_user`, `total_harga`, `status`, `tanggal_pesan`, `alamat`, `metode_pembayaran`) VALUES
(1, 2, 8000.00, 'pending', '2025-11-27 14:00:57', '', 'Qris'),
(2, 5, 35000.00, 'pending', '2025-11-29 21:36:27', '', 'Qris'),
(3, 5, 35000.00, 'selesai', '2025-11-29 22:36:45', NULL, 'Cash'),
(4, 5, 15000.00, 'selesai', '2025-11-29 23:02:13', NULL, 'Cash'),
(5, 5, 35000.00, 'selesai', '2025-11-29 23:13:16', NULL, 'Cash'),
(6, 5, 35000.00, 'pending', '2025-11-29 23:17:27', NULL, 'Qris'),
(7, 5, 35000.00, 'pending', '2025-11-29 23:20:39', NULL, 'Qris'),
(8, 5, 35000.00, 'pending', '2025-11-29 23:21:03', NULL, 'Qris'),
(9, 5, 35000.00, 'pending', '2025-11-29 23:21:50', NULL, 'Qris'),
(10, 5, 25000.00, 'selesai', '2025-11-29 23:22:20', NULL, 'Qris'),
(11, 5, 25000.00, 'dibatalkan', '2025-11-29 23:24:18', NULL, 'Cash'),
(12, 5, 90000.00, 'selesai', '2025-11-30 00:03:47', NULL, 'Qris'),
(13, 5, 25000.00, 'selesai', '2025-11-30 00:03:59', NULL, 'Cash'),
(14, 5, 30000.00, 'selesai', '2025-12-01 00:29:57', NULL, 'Qris'),
(15, 5, 70000.00, 'selesai', '2025-12-15 09:08:58', NULL, 'Qris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password_hash`, `nama`, `email`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'ica cantik', NULL, 'admin', '2025-11-09 17:37:33'),
(2, 'raisa', '$2y$10$ZXiMC2HmVqL.IVDb3iDw0OAr4q75L6FqSh7s9QsQ8JQCNLyvFDZ5K', 'raisayml', 'raisa@gmail.com', 'customer', '2025-11-25 11:59:52'),
(5, 'ica', '12', 'ica', 'ica@gmail.com', 'customer', '2025-11-28 04:03:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indeks untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `kategori_menu`
--
ALTER TABLE `kategori_menu`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_menu` (`id_kategori`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

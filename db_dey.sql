-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Apr 2025 pada 03.08
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dey`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_pmb`
--

CREATE TABLE `data_pmb` (
  `id` int(11) NOT NULL,
  `peserta` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `jenis_sekolah` varchar(100) DEFAULT NULL,
  `prodi_pilihan` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `berkas` varchar(150) DEFAULT NULL,
  `status_tes` varchar(50) DEFAULT NULL,
  `status_bayar` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `data_pmb`
--

INSERT INTO `data_pmb` (`id`, `peserta`, `kecamatan`, `jenis_sekolah`, `prodi_pilihan`, `username`, `password`, `berkas`, `status_tes`, `status_bayar`, `email`, `alamat`) VALUES
(1, 'Aditya Pratama', 'Jatinangor', 'SMA Negeri', 'Teknik Informatika', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Siti Nurhaliza', 'Sumedang', 'MA Swasta', 'Kedokteran', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Rizky Saputra', 'Cimanggung', 'SMK Negeri', 'Manajemen', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Lina Marlina', 'Tanjungsari', 'SMA Swasta', 'Akuntansi', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Andi Wijaya', 'Cileunyi', 'SMA Negeri', 'Hukum', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Prades', 'Cimanggung', 'SMA', 'Sistem Informasi', 'Prades', '123', 'berkas_prades', 'Lulus', 'Belum', NULL, NULL),
(9, 'Tia Prades', 'Jatinangor', 'SMA', 'Manajemen', 'Prades', '2929', 'dey_pdf', 'Lulus', 'Sudah', NULL, NULL),
(12346, 'Dea Pradestiawati', NULL, 'SMAN Cimanggung', 'Komputerisasi akuntansi', NULL, NULL, NULL, NULL, NULL, 'watiku@gmail.com', NULL),
(12348, 'Yaya Nuryah', NULL, 'SMA Negri', 'Komputerisasi akuntansi', NULL, NULL, NULL, NULL, NULL, 'watiku@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id_jadwal` int(11) NOT NULL,
  `id_mata_kuliah` int(11) DEFAULT NULL,
  `hari` varchar(10) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `dosen_pengampu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id_jadwal`, `id_mata_kuliah`, `hari`, `jam_mulai`, `jam_selesai`, `dosen_pengampu`) VALUES
(1, 1, 'Senin', '08:00:00', '10:00:00', 'Dr. John Doe'),
(2, 2, 'Selasa', '10:00:00', '12:00:00', 'Prof. Jane Smith'),
(3, 3, 'Rabu', '14:00:00', '16:00:00', 'Dr. Robert Brown');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_tes`
--

CREATE TABLE `jadwal_tes` (
  `id_jadwal` int(11) NOT NULL,
  `tanggal_tes` date NOT NULL,
  `waktu_tes` time NOT NULL,
  `lokasi_tes` varchar(255) NOT NULL,
  `status_tes` enum('aktif','tidak aktif') NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jadwal_tes`
--

INSERT INTO `jadwal_tes` (`id_jadwal`, `tanggal_tes`, `waktu_tes`, `lokasi_tes`, `status_tes`, `id_petugas`, `created_at`) VALUES
(1, '2025-05-10', '09:00:00', 'Ruang A1, Kampus PMB', 'aktif', 1, '2025-04-22 23:12:07'),
(2, '2025-05-11', '13:00:00', 'Ruang B2, Kampus PMB', 'aktif', 2, '2025-04-22 23:12:07'),
(3, '2025-05-12', '08:30:00', 'Ruang C3, Kampus PMB', 'aktif', 3, '2025-04-22 23:12:07'),
(4, '2025-05-13', '10:00:00', 'Ruang D4, Kampus PMB', 'tidak aktif', 1, '2025-04-22 23:12:07'),
(5, '2025-05-01', '06:33:00', 'Ruang B301, Kampus PMB', 'aktif', 1, '2025-04-22 23:19:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_keuangan`
--

CREATE TABLE `konfigurasi_keuangan` (
  `id` int(11) NOT NULL,
  `formulir` int(11) NOT NULL DEFAULT 0,
  `registrasi_ulang` int(11) NOT NULL DEFAULT 0,
  `per_tahun` int(11) NOT NULL DEFAULT 0,
  `payment_gateway` varchar(50) NOT NULL DEFAULT 'Manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `konfigurasi_keuangan`
--

INSERT INTO `konfigurasi_keuangan` (`id`, `formulir`, `registrasi_ulang`, `per_tahun`, `payment_gateway`) VALUES
(1, 100000, 200000, 300000, 'Manual');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi_umum`
--

CREATE TABLE `konfigurasi_umum` (
  `id` int(11) NOT NULL,
  `nama_institusi` varchar(255) DEFAULT NULL,
  `tahun_akademik` varchar(20) DEFAULT NULL,
  `periode_pendaftaran` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `konfigurasi_umum`
--

INSERT INTO `konfigurasi_umum` (`id`, `nama_institusi`, `tahun_akademik`, `periode_pendaftaran`) VALUES
(3, 'SMAN', '2026', '2026');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kurikulum`
--

CREATE TABLE `kurikulum` (
  `id_kurikulum` int(11) NOT NULL,
  `prodi_id` int(11) DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `mata_kuliah_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kurikulum`
--

INSERT INTO `kurikulum` (`id_kurikulum`, `prodi_id`, `tahun_ajaran`, `mata_kuliah_id`) VALUES
(1, 1, '2024/2025', 1),
(2, 1, '2024/2025', 2),
(3, 2, '2024/2025', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `prodi_pilihan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama_lengkap`, `email`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `prodi_pilihan`) VALUES
(1, 'Andi Prasetyo', 'andi@example.com', '2000-04-10', 'L', 'Jl. Merdeka No. 123, Jakarta', 1),
(2, 'Budi Santoso', 'budi@example.com', '1999-07-15', 'L', 'Jl. Raya No. 45, Bandung', 2),
(3, 'Citra Dewi', 'citra@example.com', '2001-05-20', 'P', 'Jl. Mawar No. 34, Surabaya', 3),
(4, 'Diana Anggraini', 'diana@example.com', '2000-08-11', 'P', 'Jl. Kuning No. 10, Yogyakarta', 4),
(5, 'Eko Prabowo', 'eko@example.com', '1999-12-25', 'L', 'Jl. Suka No. 15, Bali', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_mata_kuliah` int(11) NOT NULL,
  `kode_mata_kuliah` varchar(20) NOT NULL,
  `nama_mata_kuliah` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_mata_kuliah`, `kode_mata_kuliah`, `nama_mata_kuliah`, `sks`) VALUES
(1, 'MK001', 'Pemrograman Web', 3),
(2, 'MK002', 'Struktur Data', 3),
(3, 'MK003', 'Jaringan Komputer', 3),
(4, 'MK004', 'Basis Data', 3),
(5, 'MK005', 'Sistem Operasi', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai_mahasiswa`
--

CREATE TABLE `nilai_mahasiswa` (
  `id_nilai` int(11) NOT NULL,
  `id_mahasiswa` int(11) DEFAULT NULL,
  `id_mata_kuliah` int(11) DEFAULT NULL,
  `nilai` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `nilai_mahasiswa`
--

INSERT INTO `nilai_mahasiswa` (`id_nilai`, `id_mahasiswa`, `id_mata_kuliah`, `nilai`) VALUES
(1, 1, 1, 'A'),
(2, 1, 2, 'B'),
(3, 2, 1, 'C'),
(4, 3, 3, 'B');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id_pendaftaran` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status` enum('Lunas','Belum Lunas') NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `nama`, `id_pendaftaran`, `jumlah`, `status`, `tanggal`) VALUES
(1, 'Aditya Pratama', 'PMB20250123', 10000000, 'Lunas', '2025-03-09'),
(2, 'Siti Nurhaliza', 'PMB20250145', 5000000, 'Belum Lunas', '0000-00-00'),
(3, 'Peserta Tidak Dikenal', 'PMB20250125', 5000000, 'Lunas', '2025-04-20'),
(4, 'John Doe', '12345', 5000000, 'Lunas', '2025-04-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id_pendaftaran` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `jenis_sekolah` varchar(255) NOT NULL,
  `prodi_pilihan` varchar(255) NOT NULL,
  `berkas_ijazah` varchar(255) DEFAULT NULL,
  `berkas_kk` varchar(255) DEFAULT NULL,
  `berkas_foto` varchar(255) DEFAULT NULL,
  `status` enum('belum','diterima','ditolak') DEFAULT 'belum',
  `verifikasi_ijazah` tinyint(1) DEFAULT 0,
  `verifikasi_kk` tinyint(1) DEFAULT 0,
  `verifikasi_foto` tinyint(1) DEFAULT 0,
  `status_verifikasi` varchar(50) DEFAULT 'Belum Terverifikasi',
  `kecamatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pendaftar`
--

INSERT INTO `pendaftar` (`id_pendaftaran`, `nama_lengkap`, `username`, `user_id`, `email`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `jenis_sekolah`, `prodi_pilihan`, `berkas_ijazah`, `berkas_kk`, `berkas_foto`, `status`, `verifikasi_ijazah`, `verifikasi_kk`, `verifikasi_foto`, `status_verifikasi`, `kecamatan`) VALUES
(12345, 'John Doe', 'john', 0, 'john@gmail.com', 'Bandung', '2029-04-03', 'Laki-laki', 'Kp. Buah Batu', 'SMA Negri', 'Manajemen', NULL, NULL, NULL, 'belum', 0, 0, 0, 'Belum Terverifikasi', ''),
(12346, 'Dea Pradestiawati', 'deya', 0, 'watiku@gmail.com', 'Sumedang', '2005-04-29', 'Perempuan', 'Kp. Bojongbolang desa. Sukadana Kec. Cimanggung rt/03 rw/05', 'SMAN Cimanggung', '5', '2.jpg', 'adams.jpg', 'Screenshot 2025-02-18 103703.jpg', 'belum', 0, 0, 0, 'Terverifikasi', ''),
(12348, 'Yaya Nuryah', 'yaya', 0, 'watiku@gmail.com', 'Bandung', '2004-02-17', 'Perempuan', 'Bojongsari', 'SMA Negri', '5', 'adams.jpg', '2.jpg', 'Screenshot 2025-02-18 103703.jpg', 'belum', 0, 0, 0, 'Belum Terverifikasi', 'Jatinangor');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(11) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id`, `nama_prodi`) VALUES
(1, 'Teknik Informatika'),
(2, 'Kedokteran'),
(3, 'Manajemen'),
(4, 'Akuntansi'),
(5, 'Komputerisasi akuntansi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `spp_bulanan`
--

CREATE TABLE `spp_bulanan` (
  `id_spp` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `status` enum('lunas','belum lunas') NOT NULL,
  `tanggal_pembayaran` date DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_petugas_pmb`
--

CREATE TABLE `tb_petugas_pmb` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_petugas_pmb`
--

INSERT INTO `tb_petugas_pmb` (`id`, `nama`, `jabatan`, `kontak`) VALUES
(1, 'dea', 'Reseptionis', '0896751229390');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_sarana`
--

CREATE TABLE `tb_sarana` (
  `id` int(11) NOT NULL,
  `nama_sarana` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `tanggal_pengadaan` date DEFAULT NULL,
  `tanggal_pemeliharaan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_sarana`
--

INSERT INTO `tb_sarana` (`id`, `nama_sarana`, `jumlah`, `keterangan`, `status`, `kategori`, `tanggal_pengadaan`, `tanggal_pemeliharaan`) VALUES
(1, 'Laboratorium Komputer', 5, 'Dilengkapi dengan 50 komputer', NULL, NULL, NULL, NULL),
(2, 'Ruang Kuliah', 20, '', NULL, NULL, NULL, NULL),
(4, 'Laptop Dell XPS', 5, 'Sedang dalam proses perbaikan keyboard', 'dalam pemeliharaan', 'Elektronik', '2023-05-01', '2025-01-15'),
(5, 'AC Panasonic', 2, 'Butuh pembersihan rutin dan penggantian filter', 'dalam pemeliharaan', 'Peralatan Kantor', '2022-11-15', '2025-04-30'),
(6, 'Kursi Kantor', 10, 'Kursi mengalami kerusakan pada kaki belakang', 'dalam pemeliharaan', 'Furnitur', '2024-02-20', '2025-05-10'),
(7, 'Proyektor Epson', 3, 'Lampu proyektor sudah mulai redup', 'dalam pemeliharaan', 'Elektronik', '2023-07-01', '2025-03-20'),
(8, 'Papan Tulis', 1, 'Permukaan papan mulai usang dan perlu diganti', 'dalam pemeliharaan', 'Furnitur', '2024-03-01', '2025-04-25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users`
--

CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','keuangan','sarana','akademik','petugas','pendaftar') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_users`
--

INSERT INTO `tb_users` (`id`, `username`, `password`, `role`, `nama`, `jabatan`, `kontak`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$JzRt2O0OlsWXS5bflEp1JeXRSaDFedjZ/UgskzAVQuvyWMEPcQjVy', 'admin', '', NULL, NULL, NULL),
(2, 'ahmad', '$2y$10$IYIL8fiEWAmd6mVGI/YJluiEHpFdkisVLKc9WkOnxU.UtfqSAA9t6', 'akademik', '', NULL, NULL, NULL),
(3, 'budi', '$2y$10$KKDU9RbK8TL1aT9loUbNXe0mHPmJRSD2/j2y150sKt/psHgkAbaGK', 'keuangan', '', NULL, NULL, NULL),
(4, 'charlie', '$2y$10$q3pOuueDsYJdrxGDNiUIJOL6Z5QyxmpYIi2ccpvPmrdFxWvfVUnC2', 'sarana', '', NULL, NULL, NULL),
(5, 'deya', '$2y$10$ebcmD7pFxG/mGC1C1LeAAOWnWCBfI6zFtMAjT1Ew3sJWz97dfHsxq', 'pendaftar', '', NULL, NULL, NULL),
(6, 'yaya', '$2y$10$aKucCxohVB/Av3tDJ1x.L.ykfRTpeyz5HNVNErtNCKR2sifj0RggS', 'pendaftar', '', NULL, NULL, NULL),
(7, 'ajeng', '$2y$10$ehvdKCXhv2GNWpD2JvRrf.YujVoe8gploxpAEfCRtyS3YIJLu.4Xy', 'petugas', '', NULL, NULL, 'Ajeng Komariah');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `data_pmb`
--
ALTER TABLE `data_pmb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indeks untuk tabel `jadwal_tes`
--
ALTER TABLE `jadwal_tes`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indeks untuk tabel `konfigurasi_keuangan`
--
ALTER TABLE `konfigurasi_keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `konfigurasi_umum`
--
ALTER TABLE `konfigurasi_umum`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id_kurikulum`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `prodi_pilihan` (`prodi_pilihan`);

--
-- Indeks untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_mata_kuliah`);

--
-- Indeks untuk tabel `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `spp_bulanan`
--
ALTER TABLE `spp_bulanan`
  ADD PRIMARY KEY (`id_spp`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `tb_petugas_pmb`
--
ALTER TABLE `tb_petugas_pmb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_sarana`
--
ALTER TABLE `tb_sarana`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `data_pmb`
--
ALTER TABLE `data_pmb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12349;

--
-- AUTO_INCREMENT untuk tabel `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal_tes`
--
ALTER TABLE `jadwal_tes`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_keuangan`
--
ALTER TABLE `konfigurasi_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi_umum`
--
ALTER TABLE `konfigurasi_umum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id_kurikulum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_mata_kuliah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `nilai_mahasiswa`
--
ALTER TABLE `nilai_mahasiswa`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12349;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `spp_bulanan`
--
ALTER TABLE `spp_bulanan`
  MODIFY `id_spp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tb_petugas_pmb`
--
ALTER TABLE `tb_petugas_pmb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_sarana`
--
ALTER TABLE `tb_sarana`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `jadwal_tes`
--
ALTER TABLE `jadwal_tes`
  ADD CONSTRAINT `jadwal_tes_ibfk_1` FOREIGN KEY (`id_petugas`) REFERENCES `tb_users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`prodi_pilihan`) REFERENCES `program_studi` (`id`);

--
-- Ketidakleluasaan untuk tabel `spp_bulanan`
--
ALTER TABLE `spp_bulanan`
  ADD CONSTRAINT `spp_bulanan_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `pendaftar` (`id_pendaftaran`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

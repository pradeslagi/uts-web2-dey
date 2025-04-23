<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$username = $_SESSION['username'];

// Ambil data user dari database
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika tidak ada user ditemukan, logout paksa
    session_destroy();
    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();
$role = $user['role'];
$nama = ucfirst(htmlspecialchars($user['username'])); // Pastikan ini sudah benar

// Identifikasi role
$is_admin     = ($role === 'admin');
$is_keuangan  = ($role === 'keuangan');
$is_akademik  = ($role === 'akademik');
$is_sarana    = ($role === 'sarana');
$is_pendaftar = ($role === 'pendaftar');
$is_petugas = ($role === 'petugas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        #sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #333;
            color: white;
            transition: left 0.3s ease;
            padding-top: 60px;
            z-index: 1000;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
        }

        #sidebar ul li {
            padding: 12px;
            text-align: center;
        }

        #sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        #sidebar ul li a:hover {
            background-color: #575757;
        }

        #hamburger {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 30px;
            cursor: pointer;
            z-index: 2000;
            color: #333;
        }

        .dashboard-container {
            margin-left: 20px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .admin-info {
            margin: 15px 0;
            padding: 12px;
            background: #fef9e7;
            border-left: 4px solid #f1c40f;
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <ul>
        <?php if ($is_admin): ?>
            <li><a href="konfigurasi_system.php">Konfigurasi Sistem</a></li>
            <li><a href="tambah_petugas_pmb.php">Tambah Petugas PMB</a></li>
            <li><a href="data_akademik.php">Data Akademik</a></li>
            <li><a href="data_pendaftar.php">Data PMB Baru</a></li>
            <li><a href="kelola_jadwal_tes.php">Jadwal Tes</a></li>
        <?php endif; ?>

        <?php if ($is_keuangan): ?>
            <li><a href="data_keuangan.php">Data Keuangan</a></li>
        <?php endif; ?>

        <?php if ($is_akademik): ?>
            <li><a href="data_akademik.php">Data Akademik</a></li>
            <li><a href="data_pendaftar.php">Data PMB</a></li>
            <li><a href="jadwal_kuliah.php">Jadwal Perkuliahan</a></li>
            <li><a href="mata_kuliah.php">Mata Kuliah</a></li>
            <li><a href="nilai_mahasiswa.php">Nilai Akademik</a></li>
        <?php endif; ?>

        <?php if ($is_sarana): ?>
            <li><a href="data_sarana.php">Data Sarana</a></li>
            <li><a href="kelola_sarana_pemeliharaan.php">Data Pemeliharaan</a></li>
        <?php endif; ?>

        <?php if ($is_pendaftar): ?>
            <li><a href="pendaftaran.php">Formulir Pendaftaran</a></li>
            <li><a href="data_pendaftar.php">Data PMB</a></li>
        <?php endif; ?>

        <?php if ($is_petugas): ?>
            <li><a href="data_akademik.php">Data Akademik</a></li>
            <li><a href="data_pendaftar.php">Data PMB Baru</a></li>
        <?php endif; ?>

        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Hamburger -->
<div id="hamburger">&#9776;</div>

<!-- Main Dashboard Content -->
<div class="dashboard-container">
    <h2>Selamat Datang, <?= htmlspecialchars($nama) ?>!</h2>
    <p>Role Anda: <strong style="color: darkblue"><?= htmlspecialchars($role) ?></strong></p>

    <?php if ($is_admin): ?>
        <div class="admin-info">
            Anda login sebagai <strong>Administrator</strong>. Anda memiliki akses penuh untuk mengelola data sistem PMB.
        </div>
    <?php endif; ?>

    <?php if ($is_keuangan): ?>
        <div class="admin-info" style="border-left-color: #3498db; background: #ecf5fd;">
            Anda login sebagai <strong>Keuangan</strong>. Silakan kelola data keuangan di menu yang tersedia.
        </div>
    <?php endif; ?>

    <?php if ($is_akademik): ?>
        <div class="admin-info" style="border-left-color: #3498db; background: #ecf5fd;">
            Anda login sebagai <strong>Akademik</strong>. Silakan kelola data akademik di menu yang tersedia.
        </div>
    <?php endif; ?>

    <?php if ($is_sarana): ?>
        <div class="admin-info" style="border-left-color: #3498db; background: #ecf5fd;">
            Anda login sebagai <strong>Sarana</strong>. Silakan kelola data sarana di menu yang tersedia.
        </div>
    <?php endif; ?>

    <?php if ($is_pendaftar): ?>
        <div class="admin-info" style="border-left-color: #3498db; background: #ecf5fd;">
            Anda login sebagai <strong>Pendaftar</strong>. Silakan lengkapi formulir pendaftaran Anda melalui menu.
        </div>
    <?php endif; ?>

    <p>Gunakan menu di samping kiri untuk mengakses fitur yang tersedia.</p>
</div>

<script>
    document.getElementById("hamburger").onclick = function () {
        var sidebar = document.getElementById("sidebar");
        var dashboard = document.querySelector(".dashboard-container");
        if (sidebar.style.left === "-250px") {
            sidebar.style.left = "0";
            dashboard.style.marginLeft = "250px";
        } else {
            sidebar.style.left = "-250px";
            dashboard.style.marginLeft = "20px";
        }
    };
</script>

</body>
</html>

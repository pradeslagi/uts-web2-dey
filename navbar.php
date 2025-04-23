<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");

// Ambil data user saat ini
$username = $_SESSION['username'];
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Menambahkan variabel is_admin
$is_admin = $user['role'] === 'admin';

if (!$is_admin) {
    echo "Anda tidak memiliki hak akses.";
    exit;
}
?>

<!-- Sisa HTML dan Navbar -->

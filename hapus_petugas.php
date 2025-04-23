<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");

// Ambil data user saat ini
$username = $_SESSION['username'];
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    echo "Anda tidak memiliki hak akses.";
    exit;
}

// Hapus petugas
if (isset($_GET['id'])) {
    $id_petugas = $_GET['id'];

    // Hapus petugas dari tb_users
    $stmtDelete = $koneksi->prepare("DELETE FROM tb_users WHERE id = ? AND role = 'petugas'");
    $stmtDelete->bind_param("i", $id_petugas);
    $stmtDelete->execute();

    header("Location: daftar_petugas.php");
    exit;
} else {
    echo "ID petugas tidak ditemukan.";
    exit;
}
?>

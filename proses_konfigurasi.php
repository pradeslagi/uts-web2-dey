<?php
session_start();

// Cek role dan username
if ($_SESSION['username'] !== 'budi' || $_SESSION['role'] !== 'keuangan') {
  die("Akses ditolak. Hanya Budi yang dapat mengatur konfigurasi.");
}

// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_dey";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$cara_bayar = $_POST['cara_bayar'] ?? '';
$nominal_pembayaran_formulir = $_POST['nominal_pembayaran_formulir'] ?? '';
$nominal_registrasi_ulang = $_POST['nominal_registrasi_ulang'] ?? '';
$nominal_pembayaran_per_tahun = $_POST['nominal_pembayaran_per_tahun'] ?? '';
$payment_gateway = $_POST['payment_gateway'] ?? '';

// Validasi input
if (empty($cara_bayar) || !is_numeric($nominal_pembayaran_formulir) || !is_numeric($nominal_registrasi_ulang) || !is_numeric($nominal_pembayaran_per_tahun) || empty($payment_gateway)) {
    echo "<script>alert('Data tidak valid! Pastikan semua data sudah terisi dengan benar.'); window.history.back();</script>";
    exit;
}

// Pastikan nominal pembayaran adalah angka positif
if ($nominal_pembayaran_formulir <= 0 || $nominal_registrasi_ulang <= 0 || $nominal_pembayaran_per_tahun <= 0) {
    echo "<script>alert('Nominal pembayaran harus lebih besar dari 0!'); window.history.back();</script>";
    exit;
}

// Update atau Insert konfigurasi
$query = "INSERT INTO konfigurasi_keuangan (cara_bayar, nominal_pembayaran_formulir, nominal_registrasi_ulang, nominal_pembayaran_per_tahun, payment_gateway)
          VALUES (?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
          cara_bayar = VALUES(cara_bayar), 
          nominal_pembayaran_formulir = VALUES(nominal_pembayaran_formulir),
          nominal_registrasi_ulang = VALUES(nominal_registrasi_ulang),
          nominal_pembayaran_per_tahun = VALUES(nominal_pembayaran_per_tahun),
          payment_gateway = VALUES(payment_gateway)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sddds", $cara_bayar, $nominal_pembayaran_formulir, $nominal_registrasi_ulang, $nominal_pembayaran_per_tahun, $payment_gateway);

if ($stmt->execute()) {
    echo "<script>alert('Konfigurasi berhasil disimpan!'); window.location.href='keuangan.php';</script>";
} else {
    echo "<script>alert('Gagal menyimpan konfigurasi! Error: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>

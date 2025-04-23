<?php
session_start();

// Cek role dan username
if ($_SESSION['username'] !== 'budi' || $_SESSION['role'] !== 'keuangan') {
  die("Akses ditolak. Hanya Budi yang dapat menambahkan pembayaran.");
}

// Cek jika data dikirim lewat POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idPendaftaran = $_POST['id_pendaftaran'] ?? '';
  $jumlah = $_POST['jumlah'] ?? '';

  // Validasi sederhana
  if (empty($idPendaftaran) || empty($jumlah) || !is_numeric($jumlah)) {
    echo "<script>alert('Data tidak lengkap atau jumlah tidak valid!'); window.history.back();</script>";
    exit;
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

  // Ambil nama dari tabel pendaftar berdasarkan ID pendaftaran (kalau ada)
  $queryNama = "SELECT nama FROM pendaftar WHERE id_pendaftaran = ?";
  $stmtNama = $conn->prepare($queryNama);
  $stmtNama->bind_param("s", $idPendaftaran);
  $stmtNama->execute();
  $resultNama = $stmtNama->get_result();

  if ($resultNama->num_rows > 0) {
    $row = $resultNama->fetch_assoc();
    $nama = $row['nama'];
  } else {
    $nama = "Peserta Tidak Dikenal"; // fallback
  }

  // Simpan pembayaran ke tabel pembayaran
  $query = "INSERT INTO pembayaran (id_pendaftaran, nama, jumlah, status, tanggal)
            VALUES (?, ?, ?, 'Lunas', NOW())";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ssi", $idPendaftaran, $nama, $jumlah);

  if ($stmt->execute()) {
    echo "<script>alert('Pembayaran berhasil ditambahkan untuk ID $idPendaftaran'); window.location.href='data_keuangan.php';</script>";
  } else {
    echo "<script>alert('Gagal menyimpan pembayaran: " . $conn->error . "'); window.history.back();</script>";
  }

  $stmt->close();
  $conn->close();
} else {
  // Kalau bukan POST request
  echo "<script>alert('Akses tidak valid'); window.location.href='data_keuangan.php';</script>";
  exit;
}
?>

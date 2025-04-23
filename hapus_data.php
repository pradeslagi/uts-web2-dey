<?php
session_start();
$role = $_SESSION['role'] ?? '';
$fullAccess = ($role == 'admin' || $role == 'akademik');

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Koneksi ke database
  $conn = new mysqli("localhost", "root", "", "db_dey");
  if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
  }

  // Query untuk menghapus data
  $sql = "DELETE FROM data_pmb WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: data_akademik.php"); // Redirect setelah sukses
    exit;
  } else {
    echo "Gagal menghapus data!";
  }

  $conn->close();
} else {
  echo "ID tidak diberikan!";
}

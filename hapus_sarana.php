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

$id = $_GET['id'];
$koneksi->query("DELETE FROM tb_sarana WHERE id = $id");

header("Location: data_sarana.php");
exit;
?>

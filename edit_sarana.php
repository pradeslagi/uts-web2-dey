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
$result = $koneksi->query("SELECT * FROM tb_sarana WHERE id = $id");
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_sarana'];
  $jumlah = $_POST['jumlah'];
  $keterangan = $_POST['keterangan'];

  $stmt = $koneksi->prepare("UPDATE tb_sarana SET nama_sarana=?, jumlah=?, keterangan=? WHERE id=?");
  $stmt->bind_param("sisi", $nama, $jumlah, $keterangan, $id);
  $stmt->execute();

  header("Location: data_sarana.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Sarana</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    input, button { padding: 10px; width: 100%; margin-bottom: 10px; }
    button { background-color: #5cb85c; color: white; border: none; }
  </style>
</head>
<body>
  <h2>Edit Sarana</h2>
  <form method="POST">
    <input type="text" name="nama_sarana" value="<?= htmlspecialchars($data['nama_sarana']) ?>" required>
    <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" required>
    <input type="text" name="keterangan" value="<?= htmlspecialchars($data['keterangan']) ?>">
    <button type="submit">Simpan Perubahan</button>
  </form>
</body>
</html>

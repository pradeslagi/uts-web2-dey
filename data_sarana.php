<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}

$username = $_SESSION['username'];
$koneksi = new mysqli("localhost", "root", "", "db_dey");
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data user dan role-nya
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$role = $user['role'];
$is_full_access = ($role === 'sarana');
$access_status = $is_full_access ? 'Full Access' : 'Read Only';

// Proses penambahan sarana
if ($is_full_access && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = $_POST['nama_sarana'];
  $jumlah = $_POST['jumlah_sarana'];
  $keterangan = $_POST['keterangan_sarana'];

  $stmt = $koneksi->prepare("INSERT INTO tb_sarana (nama_sarana, jumlah, keterangan) VALUES (?, ?, ?)");
  $stmt->bind_param("sis", $nama, $jumlah, $keterangan);
  $stmt->execute();
  header("Location: data_sarana.php");
  exit;
}

$sarana = $koneksi->query("SELECT * FROM tb_sarana");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Sarana Kampus</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
    }
    .header {
      background-color: #5bc0de;
      color: white;
      padding: 15px;
      text-align: center;
      position: relative;
    }
    .back-button {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      background-color: transparent;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 24px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .back-button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .container {
      margin: 20px;
    }
    .section {
      background: white;
      padding: 15px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }
    .section h2 {
      margin-bottom: 10px;
      font-size: 18px;
      color: #5bc0de;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      background-color: #5bc0de;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    button:hover {
      background-color: #31b0d5;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #5bc0de;
      color: white;
    }
    .aksi-button a button {
      margin: 2px;
    }
    .hapus-button {
      background-color: #d9534f;
    }
    .hapus-button:hover {
      background-color: #c9302c;
    }
  </style>
</head>

<body>
  <div class="header">
    <a href="dashboard.php" class="back-button">&#8592;</a>
    <h1>Data Sarana Kampus</h1>
    <p>Status Akses: <?= $access_status; ?></p>
  </div>

  <div class="container">

    <?php if ($is_full_access): ?>
    <div class="section">
      <h2>Tambah Sarana Kampus</h2>
      <form method="POST">
        <label for="nama-sarana">Nama Sarana</label>
        <input type="text" id="nama-sarana" name="nama_sarana" placeholder="Masukkan nama sarana" required>

        <label for="jumlah-sarana">Jumlah Sarana</label>
        <input type="number" id="jumlah-sarana" name="jumlah_sarana" placeholder="Masukkan jumlah" required>

        <label for="keterangan-sarana">Keterangan</label>
        <input type="text" id="keterangan-sarana" name="keterangan_sarana" placeholder="Masukkan keterangan (opsional)">

        <button type="submit">Tambah Sarana</button>
      </form>
    </div>
    <?php endif; ?>

    <div class="section">
      <h2>Daftar Sarana</h2>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Sarana</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $sarana->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_sarana']) ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= htmlspecialchars($row['keterangan']) ?: '-' ?></td>
            <td class="aksi-button">
              <?php if ($is_full_access): ?>
                <a href="edit_sarana.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
                <a href="hapus_sarana.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">
                  <button class="hapus-button">Hapus</button>
                </a>
              <?php else: ?>
                <em>Read Only</em>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>
</body>
</html>

<?php
session_start();
$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Akses penuh hanya untuk Budi dari bagian keuangan
$aksesPenuh = ($username === 'budi' && $role === 'keuangan');

// Role lain yang hanya bisa melihat: admin, ahmad, charlie
$hanyaLihat = in_array($role, ['admin', 'keuangan']) || in_array($username, ['ahmad', 'charlie']);

// Ambil data dari file JSON
$file = 'data_pembayaran.json';
$dataPembayaran = [];

if (file_exists($file)) {
  $dataPembayaran = json_decode(file_get_contents($file), true);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Keuangan PMB</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .header {
      background-color: #f39c12;
      color: white;
      padding: 15px;
      text-align: center;
      position: relative;
    }

    .back-button {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      font-size: 24px;
      color: white;
      cursor: pointer;
    }

    .container {
      margin: 20px;
    }

    .section {
      background: white;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .section h2 {
      font-size: 18px;
      color: #f39c12;
      margin-bottom: 10px;
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
      background-color: #f39c12;
      color: white;
    }

    button {
      background-color: #f39c12;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e67e22;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="header">
    <button class="back-button" onclick="window.location.href='dashboard.php'">&#8592;</button>
    <h1>Dashboard Keuangan PMB</h1>
  </div>

  <div class="container">

    <!-- Monitoring -->
    <div class="section">
      <h2>Monitoring Pembayaran</h2>
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Peserta</th>
            <th>ID Pendaftaran</th>
            <th>Status Pembayaran</th>
            <th>Tanggal Pembayaran</th>
            <?php if ($aksesPenuh): ?>
              <th>Aksi</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if (count($dataPembayaran) === 0): ?>
            <tr><td colspan="<?= $aksesPenuh ? 6 : 5 ?>">Belum ada data pembayaran</td></tr>
          <?php else: ?>
            <?php $no = 1; foreach ($dataPembayaran as $item): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['nama']) ?></td>
                <td><?= htmlspecialchars($item['id_pendaftaran']) ?></td>
                <td><?= htmlspecialchars($item['status']) ?></td>
                <td><?= htmlspecialchars($item['tanggal']) ?></td>
                <?php if ($aksesPenuh): ?>
                  <td><button onclick="alert('Verifikasi berhasil!')">Verifikasi</button></td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Rekap -->
    <div class="section">
      <h2>Rekapitulasi Pembayaran</h2>
      <?php
      $lunas = 0;
      $belum = 0;
      foreach ($dataPembayaran as $item) {
        if (strtolower($item['status']) === 'lunas') $lunas += 10000000;
        else $belum += 5000000;
      }
      ?>
      <p>Total Pembayaran Lunas: Rp <?= number_format($lunas, 0, ',', '.') ?></p>
      <p>Total Pembayaran Belum Lunas: Rp <?= number_format($belum, 0, ',', '.') ?></p>
    </div>

    <!-- Tambah Pembayaran -->
    <?php if ($aksesPenuh): ?>
    <div class="section">
      <h2>Tambah Pembayaran Manual</h2>
      <form action="proses_tambah_bayar.php" method="post">
        <label for="nama">Nama Peserta</label>
        <input type="text" name="nama" id="nama" required>

        <label for="id_pendaftaran">ID Pendaftaran</label>
        <input type="text" name="id_pendaftaran" id="id_pendaftaran" required>

        <label for="jumlah">Jumlah Pembayaran</label>
        <input type="number" name="jumlah" id="jumlah" required>

        <button type="submit">Tambah Pembayaran</button>
      </form>
    </div>
    <?php endif; ?>

  </div>
</body>
</html>

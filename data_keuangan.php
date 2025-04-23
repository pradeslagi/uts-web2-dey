<?php
session_start();
$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

// Budi punya akses penuh
$budiAkses = ($username === 'budi' && $role === 'keuangan');
// User lain hanya bisa lihat monitoring pembayaran
$readOnlyAkses = in_array($username, ['admin', 'ahmad', 'charlie']);

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel pembayaran
$sql = "SELECT * FROM pembayaran";
$result = $conn->query($sql);

$total_lunas = 0;
$total_belum = 0;
$pembayaran = [];

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $pembayaran[] = $row;
    if (strtolower($row['status']) === 'lunas') {
      $total_lunas += (int)$row['jumlah'];
    } else {
      $total_belum += (int)$row['jumlah'];
    }
  }
}

// Ambil info konfigurasi keuangan (jika ada)
$konfig_query = $conn->query("SELECT * FROM konfigurasi_keuangan LIMIT 1");
$konfig = $konfig_query ? $konfig_query->fetch_assoc() : [
  'formulir' => 0,
  'registrasi_ulang' => 0,
  'per_tahun' => 0,
  'payment_gateway' => 'Belum disetel'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Keuangan PMB</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
    .header { background: #f39c12; color: white; padding: 15px; text-align: center; position: relative; }
    .back-arrow { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 24px; color: white; text-decoration: none; }
    .container { margin: 20px; }
    .section { background: white; margin-bottom: 30px; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .section h2 { color: #f39c12; margin-top: 0; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
    th { background: #f39c12; color: white; }
    input, select, button { width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ccc; }
    button { background: #f39c12; color: white; border: none; cursor: pointer; }
    button:hover { background: #e67e22; }
  </style>
</head>
<body>

<div class="header">
  <a href="dashboard.php" class="back-arrow">&#8592;</a>
  <h1>Dashboard Keuangan PMB</h1>
</div>

<div class="container">
  <!-- Monitoring Pembayaran -->
  <div class="section">
    <h2>Monitoring Pembayaran</h2>
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Peserta</th>
          <th>ID Pendaftaran</th>
          <th>Status</th>
          <th>Tanggal</th>
          <?php if ($budiAkses): ?><th>Aksi</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($pembayaran)): ?>
          <tr><td colspan="<?= $budiAkses ? 6 : 5 ?>">Belum ada data pembayaran</td></tr>
        <?php else: ?>
          <?php $no = 1; foreach ($pembayaran as $data): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($data['nama']) ?></td>
              <td><?= htmlspecialchars($data['id_pendaftaran']) ?></td>
              <td><?= htmlspecialchars($data['status']) ?></td>
              <td><?= ($data['status'] === 'Lunas' && $data['tanggal']) ? htmlspecialchars($data['tanggal']) : '-' ?></td>
              <?php if ($budiAkses): ?>
                <td><button onclick="verifikasi('<?= $data['id_pendaftaran'] ?>')">Verifikasi</button></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Rekapitulasi -->
  <div class="section">
    <h2>Rekapitulasi Pembayaran</h2>
    <p>Total Lunas: <strong>Rp <?= number_format($total_lunas, 0, ',', '.') ?></strong></p>
    <p>Total Belum Lunas: <strong>Rp <?= number_format($total_belum, 0, ',', '.') ?></strong></p>
  </div>

  <!-- Tambah Pembayaran Manual -->
  <?php if ($budiAkses): ?>
    <div class="section">
      <h2>Tambah Pembayaran Manual</h2>
      <form method="post" action="proses_tambah_pembayaran.php">
        <label>ID Pendaftaran</label>
        <input type="text" name="id_pendaftaran" required>
        <label>Jumlah Pembayaran</label>
        <input type="number" name="jumlah" required>
        <button type="submit">Tambah Pembayaran</button>
      </form>
    </div>
  <?php endif; ?>

  <!-- Konfigurasi Keuangan -->
  <?php if ($budiAkses): ?>
    <div class="section">
      <h2>Konfigurasi Keuangan</h2>
      <form method="post" action="proses_konfigurasi.php">
        <label>Biaya Formulir</label>
        <input type="number" name="formulir" value="<?= $konfig['formulir'] ?>">
        <label>Registrasi Ulang</label>
        <input type="number" name="registrasi_ulang" value="<?= $konfig['registrasi_ulang'] ?>">
        <label>Biaya Per Tahun PMB</label>
        <input type="number" name="per_tahun" value="<?= $konfig['per_tahun'] ?>">
        <label>Payment Gateway</label>
        <select name="payment_gateway">
          <option <?= $konfig['payment_gateway'] == 'Xendit' ? 'selected' : '' ?>>Xendit</option>
          <option <?= $konfig['payment_gateway'] == 'Midtrans' ? 'selected' : '' ?>>Midtrans</option>
          <option <?= $konfig['payment_gateway'] == 'Manual' ? 'selected' : '' ?>>Manual</option>
        </select>
        <button type="submit">Simpan Konfigurasi</button>
      </form>
    </div>
  <?php endif; ?>

  <!-- Cara Pembayaran -->
  <div class="section">
    <h2>Informasi Cara Pembayaran</h2>
    <p>Pembayaran dapat dilakukan melalui transfer ke rekening berikut:</p>
    <ul>
      <li><strong>Bank BNI:</strong> 123456789 a.n. PMB DEY</li>
      <li><strong>Bank BCA:</strong> 987654321 a.n. PMB DEY</li>
    </ul>
  </div>
</div>

<script>
  function verifikasi(id) {
    if (confirm("Yakin verifikasi pembayaran untuk " + id + "?")) {
      window.location.href = 'verifikasi.php?id=' + id;
    }
  }
</script>

</body>
</html>

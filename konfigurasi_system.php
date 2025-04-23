<?php
// Mulai sesi
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "db_dey");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Proses simpan konfigurasi umum
if (isset($_POST['simpan_umum'])) {
    $nama_institusi = mysqli_real_escape_string($conn, $_POST['nama_institusi']);
    $tahun_akademik = mysqli_real_escape_string($conn, $_POST['tahun_akademik']);
    $periode_pendaftaran = mysqli_real_escape_string($conn, $_POST['periode_pendaftaran']);

    mysqli_query($conn, "DELETE FROM konfigurasi_umum");
    $sql = "INSERT INTO konfigurasi_umum (nama_institusi, tahun_akademik, periode_pendaftaran)
            VALUES ('$nama_institusi', '$tahun_akademik', '$periode_pendaftaran')";
    mysqli_query($conn, $sql);
}

// Proses tambah prodi
if (isset($_POST['tambah_prodi'])) {
    $prodi = mysqli_real_escape_string($conn, $_POST['prodi']);
    if (!empty($prodi)) {
        mysqli_query($conn, "INSERT INTO program_studi (nama_prodi) VALUES ('$prodi')");
    }
}

// Proses update password
if (isset($_POST['update_password'])) {
    $password = $_POST['password'];
    if (!empty($password)) {
        $password_hash = md5($password); // bisa diganti password_hash() biar lebih aman
        mysqli_query($conn, "UPDATE admin SET password='$password_hash' WHERE username='admin'");
    }
}

// Ambil data konfigurasi & prodi
$konfigurasi = mysqli_query($conn, "SELECT * FROM konfigurasi_umum LIMIT 1");
$isi_konfigurasi = mysqli_fetch_assoc($konfigurasi);

$daftar_prodi = mysqli_query($conn, "SELECT * FROM program_studi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfigurasi Sistem PMB</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
    }
    .header {
      background-color: #d9534f;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .back-link {
      padding: 15px 20px;
    }
    .back-link a {
      text-decoration: none;
      color: #d9534f;
      font-weight: bold;
    }
    .container {
      margin: 0 auto;
      max-width: 600px;
      padding: 10px;
    }
    .section {
      background: white;
      padding: 20px;
      margin-bottom: 20px;
      border: 1px solid #d9534f;
      border-radius: 5px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .section h2 {
      color: #d9534f;
    }
    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #d9534f;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #c9302c;
    }
    ul {
      padding-left: 20px;
    }
    .config-display {
      background: #f2f2f2;
      padding: 10px;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>Konfigurasi Sistem PMB</h1>
  </div>

  <div class="back-link">
    <a href="dashboard.php">&#8592; Kembali ke Dashboard</a>
  </div>

  <div class="container">
    <!-- Konfigurasi Umum -->
    <form method="POST" class="section">
      <h2>Konfigurasi Umum</h2>
      <label>Nama Institusi</label>
      <input type="text" name="nama_institusi" value="<?= htmlspecialchars($isi_konfigurasi['nama_institusi'] ?? '') ?>" required>

      <label>Tahun Akademik</label>
      <input type="text" name="tahun_akademik" value="<?= htmlspecialchars($isi_konfigurasi['tahun_akademik'] ?? '') ?>" required>

      <label>Periode Pendaftaran</label>
      <input type="text" name="periode_pendaftaran" value="<?= htmlspecialchars($isi_konfigurasi['periode_pendaftaran'] ?? '') ?>" required>

      <button type="submit" name="simpan_umum">Simpan Konfigurasi</button>

      <?php if ($isi_konfigurasi): ?>
      <div class="config-display">
        <strong>Data Tersimpan:</strong><br>
        Institusi: <?= htmlspecialchars($isi_konfigurasi['nama_institusi']) ?><br>
        Tahun Akademik: <?= htmlspecialchars($isi_konfigurasi['tahun_akademik']) ?><br>
        Periode: <?= htmlspecialchars($isi_konfigurasi['periode_pendaftaran']) ?>
      </div>
      <?php endif; ?>
    </form>

    <!-- Tambah Prodi -->
    <form method="POST" class="section">
      <h2>Program Studi</h2>
      <label>Nama Program Studi</label>
      <input type="text" name="prodi" required>
      <button type="submit" name="tambah_prodi">Tambah Prodi</button>

      <h3>Daftar Program Studi</h3>
      <ul>
        <?php while($row = mysqli_fetch_assoc($daftar_prodi)): ?>
          <li><?= htmlspecialchars($row['nama_prodi']) ?></li>
        <?php endwhile; ?>
      </ul>
    </form>

    <!-- Ubah Password -->
    <form method="POST" class="section">
      <h2>Ubah Password Admin</h2>
      <label>Password Baru</label>
      <input type="password" name="password" required>
      <button type="submit" name="update_password">Update Password</button>
    </form>
  </div>
</body>
</html>

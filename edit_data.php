<?php
session_start();
$role = $_SESSION['role'] ?? '';
$fullAccess = ($role == 'admin' || $role == 'akademik');

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Ambil data berdasarkan ID
  $sql = "SELECT * FROM data_pmb WHERE id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  } else {
    echo "Data tidak ditemukan!";
    exit;
  }
} else {
  echo "ID tidak diberikan!";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Ambil data dari form
  $peserta = $_POST['peserta'];
  $kecamatan = $_POST['kecamatan'];
  $jenis_sekolah = $_POST['jenis_sekolah'];
  $prodi_pilihan = $_POST['prodi_pilihan'];
  $username = $_POST['username'];
  $password = $_POST['password']; // Password langsung dari form input
  $berkas = $_POST['berkas'];
  $status_tes = $_POST['status_tes'];
  $status_bayar = $_POST['status_bayar'];

  // Jika password tidak diubah (dikosongkan), maka tetap gunakan password lama
  if (empty($password)) {
    $password = $row['password']; // Gunakan password lama yang ada di database
  }

  // Update data ke database
  $update_sql = "UPDATE data_pmb SET peserta=?, kecamatan=?, jenis_sekolah=?, prodi_pilihan=?, username=?, password=?, berkas=?, status_tes=?, status_bayar=? WHERE id=?";
  $stmt = $conn->prepare($update_sql);
  $stmt->bind_param("sssssssssi", $peserta, $kecamatan, $jenis_sekolah, $prodi_pilihan, $username, $password, $berkas, $status_tes, $status_bayar, $id);
  if ($stmt->execute()) {
    header("Location: data_akademik.php"); // Kembali ke halaman utama setelah sukses
    exit;
  } else {
    echo "Gagal mengupdate data!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Data PMB</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f6f9;
    }

    .header {
      background-color: #00796b;
      color: white;
      padding: 20px;
      text-align: center;
      font-size: 24px;
    }

    .container {
      width: 80%;
      margin: 20px auto;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .form-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .form-table th, .form-table td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    .form-table th {
      background-color: #4caf50;
      color: white;
    }

    .form-table td {
      background-color: #f9f9f9;
    }

    input, select {
      padding: 10px;
      width: 100%;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .submit-btn {
      background-color: #0288d1;
      padding: 12px 20px;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .submit-btn:hover {
      background-color: #0277bd;
    }

    .back-btn {
      background-color: #f44336;
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
    }

    .back-btn:hover {
      background-color: #d32f2f;
    }

    .title {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .form-container {
      max-width: 1000px;
      margin: 0 auto;
    }

    .password-container {
      position: relative;
    }

    .eye-icon {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

  </style>
</head>

<body>

  <div class="header">
    <a href="data_akademik.php" class="back-btn">&larr; Kembali ke Data PMB</a>
  </div>

  <div class="container">
    <div class="form-container">
      <div class="title">Edit Data PMB</div>

      <form action="" method="POST">
        <table class="form-table">
          <thead>
            <tr>
              <th colspan="9">Edit Data PMB</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Peserta</td>
              <td><input type="text" name="peserta" value="<?= $row['peserta'] ?>" required></td>
            </tr>
            <tr>
              <td>Kecamatan</td>
              <td><input type="text" name="kecamatan" value="<?= $row['kecamatan'] ?>" required></td>
            </tr>
            <tr>
              <td>Jenis Sekolah</td>
              <td>
                <select name="jenis_sekolah" required>
                  <option value="SMA" <?= $row['jenis_sekolah'] == 'SMA' ? 'selected' : '' ?>>SMA</option>
                  <option value="SMK" <?= $row['jenis_sekolah'] == 'SMK' ? 'selected' : '' ?>>SMK</option>
                  <option value="MA" <?= $row['jenis_sekolah'] == 'MA' ? 'selected' : '' ?>>MA</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Prodi Pilihan</td>
              <td>
                <select name="prodi_pilihan" required>
                  <option value="Teknik Informatika" <?= $row['prodi_pilihan'] == 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                  <option value="Sistem Informasi" <?= $row['prodi_pilihan'] == 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                  <option value="Manajemen" <?= $row['prodi_pilihan'] == 'Manajemen' ? 'selected' : '' ?>>Manajemen</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Username</td>
              <td><input type="text" name="username" value="<?= $row['username'] ?>" required></td>
            </tr>
            <tr>
              <td>Password</td>
              <td class="password-container">
                <input type="password" name="password" id="password" value="<?= $row['password'] ?>" placeholder="Masukkan password baru jika ingin mengubahnya">
                <span class="eye-icon" onclick="togglePassword()">👁️</span>
              </td>
            </tr>
            <tr>
              <td>Berkas</td>
              <td><input type="text" name="berkas" value="<?= $row['berkas'] ?>" placeholder="nama_berkas.pdf"></td>
            </tr>
            <tr>
              <td>Status Tes</td>
              <td>
                <select name="status_tes">
                  <option value="Belum" <?= $row['status_tes'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
                  <option value="Lulus" <?= $row['status_tes'] == 'Lulus' ? 'selected' : '' ?>>Lulus</option>
                  <option value="Tidak Lulus" <?= $row['status_tes'] == 'Tidak Lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Status Bayar</td>
              <td>
                <select name="status_bayar">
                  <option value="Belum" <?= $row['status_bayar'] == 'Belum' ? 'selected' : '' ?>>Belum</option>
                  <option value="Sudah" <?= $row['status_bayar'] == 'Sudah' ? 'selected' : '' ?>>Sudah</option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>

        <button type="submit" class="submit-btn">Update Data</button>
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      var passwordField = document.getElementById("password");
      var eyeIcon = document.querySelector(".eye-icon");

      if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.textContent = "🙈"; // Ganti ikon menjadi mata tertutup
      } else {
        passwordField.type = "password";
        eyeIcon.textContent = "👁️"; // Ganti ikon menjadi mata terbuka
      }
    }
  </script>

</body>
</html>

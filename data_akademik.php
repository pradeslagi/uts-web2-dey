<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data yang sudah diverifikasi (dari tabel data_pmb)
$sql = "SELECT * FROM data_pmb";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Akademik</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Untuk ikon Font Awesome -->
  <style>
    /* CSS styles */
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background-color: #f9f9f9;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #5bc0de;
      color: white;
    }

    td {
      background-color: white;
    }

    .btn-edit {
      background-color: #4caf50;
      padding: 5px 10px;
      color: white;
      text-decoration: none;
    }

    .btn-delete {
      background-color: #f44336;
      padding: 5px 10px;
      color: white;
      text-decoration: none;
    }

    .btn-back {
      background-color: #007bff;
      color: white;
      padding: 10px;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    .btn-back i {
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <!-- Tombol Kembali ke Dashboard -->
  <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

  <h1>Data Akademik</h1>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Peserta</th>
        <th>Kecamatan</th>
        <th>Jenis Sekolah</th>
        <th>Prodi Pilihan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
          // Menampilkan data sesuai format
          echo "<tr>
                  <td>{$no}</td>
                  <td>{$row['peserta']}</td>
                  <td>{$row['kecamatan']}</td>
                  <td>{$row['jenis_sekolah']}</td>
                  <td>{$row['prodi_pilihan']}</td>
                  <td>
                    <a href='edit_data.php?id={$row['id']}' class='btn-edit'>Edit</a>
                    <a href='hapus_data.php?id={$row['id']}' class='btn-delete' onclick='return confirm(\"Yakin ingin hapus?\")'>Hapus</a>
                  </td>
                </tr>";
          $no++;
        }
      } else {
        echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>

</html>

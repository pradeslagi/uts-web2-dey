<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "db_dey";

// Koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Cek koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proses insert data jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sanitasi
    $peserta = mysqli_real_escape_string($conn, $_POST['peserta']);
    $kecamatan = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $jenis_sekolah = mysqli_real_escape_string($conn, $_POST['jenis_sekolah']);
    $prodi_pilihan = mysqli_real_escape_string($conn, $_POST['prodi_pilihan']);

    // Query untuk memasukkan data
    $sql = "INSERT INTO data_pmb (peserta, kecamatan, jenis_sekolah, prodi_pilihan) 
            VALUES ('$peserta', '$kecamatan', '$jenis_sekolah', '$prodi_pilihan')";

    // Eksekusi query
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href = 'tambah_data.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Tutup koneksi
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data PMB</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e0f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 400px;
    }
    h2 {
      text-align: center;
      color: #008CBA;
    }
    input[type="text"], select {
      width: 100%;
      padding: 10px;
      margin: 8px 0 16px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      background-color: #008CBA;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #007bb5;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Tambah Data PMB</h2>
    <form action="tambah_data.php" method="POST">
      <input type="text" name="peserta" placeholder="Nama Peserta" required>
      <input type="text" name="kecamatan" placeholder="Kecamatan" required>
      <input type="text" name="jenis_sekolah" placeholder="Jenis Sekolah" required>
      <input type="text" name="prodi_pilihan" placeholder="Prodi Pilihan" required>
      <button type="submit">Submit</button>
    </form>
  </div>

</body>
</html>

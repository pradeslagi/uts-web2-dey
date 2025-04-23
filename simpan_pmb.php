<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$peserta = $_POST['peserta'];
$kecamatan = $_POST['kecamatan'];
$jenis_sekolah = $_POST['jenis_sekolah'];
$prodi_pilihan = $_POST['prodi_pilihan'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password
$berkas = $_POST['berkas'];
$status_tes = $_POST['status_tes'];
$status_bayar = $_POST['status_bayar'];

// Simpan ke database
$sql = "INSERT INTO data_pmb (peserta, kecamatan, jenis_sekolah, prodi_pilihan, username, password, berkas, status_tes, status_bayar)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssss", $peserta, $kecamatan, $jenis_sekolah, $prodi_pilihan, $username, $password, $berkas, $status_tes, $status_bayar);

if ($stmt->execute()) {
  header("Location: data_akademik.php");
  exit();
} else {
  echo "Gagal menyimpan data: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

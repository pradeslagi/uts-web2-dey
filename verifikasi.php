<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari tabel pendaftar
$sql = "SELECT p.id_pendaftaran, p.nama_lengkap, p.email, p.jenis_sekolah, ps.nama_prodi 
        FROM pendaftar p
        JOIN program_studi ps ON p.prodi_pilihan = ps.id";

$result = $conn->query($sql);

// Proses input ke data_pmb
if ($result->num_rows > 0) {
    // Simpan data hasil untuk ditampilkan di tabel
    $verifiedData = [];

    while ($row = $result->fetch_assoc()) {
        $check_sql = "SELECT id FROM data_pmb WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $row['id_pendaftaran']);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows == 0) {
            // Masukkan ke data_pmb
            $insert_sql = "INSERT INTO data_pmb (id, peserta, email, jenis_sekolah, prodi_pilihan) 
                           VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("issss", $row['id_pendaftaran'], $row['nama_lengkap'], $row['email'], 
                                     $row['jenis_sekolah'], $row['nama_prodi']);
            $insert_stmt->execute();
        }

        // Masukkan data ke array untuk ditampilkan
        $verifiedData[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Verifikasi Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #d1ecf1;
        }
        .success {
            text-align: center;
            padding: 10px;
            color: #28a745;
            font-weight: bold;
        }
        .back-btn {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background-color: #2c3e50;
            border-radius: 5px;
        }
        .back-btn:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

<h2>Data yang Berhasil Diverifikasi</h2>

<?php if (!empty($verifiedData)): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Peserta</th>
            <th>Email</th>
            <th>Jenis Sekolah</th>
            <th>Program Studi</th>
        </tr>
        <?php foreach ($verifiedData as $data): ?>
        <tr>
            <td><?= htmlspecialchars($data['id_pendaftaran']) ?></td>
            <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($data['email']) ?></td>
            <td><?= htmlspecialchars($data['jenis_sekolah']) ?></td>
            <td><?= htmlspecialchars($data['nama_prodi']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p class="success">Data berhasil diverifikasi dan dipindahkan!</p>
<?php else: ?>
    <p class="success">Tidak ada data yang bisa diverifikasi!</p>
<?php endif; ?>

<a href="dashboard.php" class="back-btn">← Kembali ke Dashboard</a>

</body>
</html>

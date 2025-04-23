<?php
session_start();

// Pastikan pengguna sudah login, jika belum arahkan ke dashboard.php
if (!isset($_SESSION['username'])) {
    header('Location: dashboard.php'); // Redirect ke dashboard.php jika belum login
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua data pendaftar dan nama program studi
$sql = "SELECT p.id_pendaftaran, p.nama_lengkap, p.email, p.tanggal_lahir, p.jenis_kelamin, p.alamat, p.jenis_sekolah, ps.nama_prodi 
        FROM pendaftar p
        JOIN program_studi ps ON p.prodi_pilihan = ps.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Untuk ikon Font Awesome -->
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; width: 90%; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px 15px; text-align: left; }
        th { background-color: #f39c12; color: white; }
        td { background-color: #f9f9f9; }
        tr:nth-child(even) td { background-color: #f1f1f1; }
        tr:hover td { background-color: #e0e0e0; }
        .action-links a { text-decoration: none; color: #333; font-weight: bold; }
        .action-links a:hover { color: #f39c12; }

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

    <div class="container">
        <h2>Data Pendaftar</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Asal Sekolah</th>
                    <th>Program Studi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>
                        <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                        <td><?= htmlspecialchars($row['alamat']) ?></td>
                        <td><?= htmlspecialchars($row['jenis_sekolah']) ?></td>
                        <td><?= htmlspecialchars($row['nama_prodi']) ?></td>
                        <td class="action-links">
                            <a href="verifikasi.php?id=<?= $row['id_pendaftaran'] ?>">Verifikasi</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>

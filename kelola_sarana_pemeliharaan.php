<?php
session_start();

// Jika belum login, arahkan ke halaman login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "db_dey");

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data user saat ini berdasarkan session
$username = $_SESSION['username'];
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Cek hak akses, hanya role 'sarana' yang boleh akses
if ($user['role'] !== 'sarana') {
    header('Location: dashboard.php');  // Redirect jika bukan role sarana
    exit;
}

// Proses penambahan sarana
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_sarana = $_POST['nama_sarana'];
    $kategori = $_POST['kategori'];
    $status = 'dalam pemeliharaan';  // Status tetap 'dalam pemeliharaan'
    $tanggal_pengadaan = $_POST['tanggal_pengadaan'];
    $tanggal_pemeliharaan = $_POST['tanggal_pemeliharaan'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];

    // Simpan ke tabel tb_sarana
    $stmtInsert = $koneksi->prepare("INSERT INTO tb_sarana (nama_sarana, kategori, status, tanggal_pengadaan, tanggal_pemeliharaan, jumlah, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("sssssss", $nama_sarana, $kategori, $status, $tanggal_pengadaan, $tanggal_pemeliharaan, $jumlah, $keterangan);
    $stmtInsert->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil semua data sarana dalam pemeliharaan
$sarana_result = $koneksi->query("SELECT * FROM tb_sarana WHERE status = 'dalam pemeliharaan'");

if ($sarana_result === false) {
    echo "Terjadi kesalahan: " . $koneksi->error;
} else {
    if ($sarana_result->num_rows > 0) {
        // Menampilkan data sarana
        while ($sarana = $sarana_result->fetch_assoc()) {
            echo "Data Sarana: " . $sarana['nama_sarana'] . "<br>";
        }
    } else {
        echo "Tidak ada data sarana dalam pemeliharaan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Sarana Dalam Pemeliharaan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Style tetap seperti sebelumnya */
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container">
    <h1>Kelola Sarana Dalam Pemeliharaan</h1>

    <h2>Tambah Sarana Pemeliharaan</h2>
    <form method="POST">
        <input type="text" name="nama_sarana" placeholder="Nama Sarana" required>
        <input type="text" name="kategori" placeholder="Kategori Sarana" required>
        <input type="date" name="tanggal_pengadaan" required>
        <input type="date" name="tanggal_pemeliharaan" required>
        <input type="number" name="jumlah" placeholder="Jumlah" required>
        <textarea name="keterangan" placeholder="Keterangan" rows="4"></textarea>
        <input type="hidden" name="status" value="dalam pemeliharaan">
        <button type="submit">Simpan Sarana Pemeliharaan</button>
    </form>

    <h2>Data Sarana Dalam Pemeliharaan</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Sarana</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Tanggal Pengadaan</th>
                <th>Tanggal Pemeliharaan</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sarana = $sarana_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($sarana['nama_sarana']) ?></td>
                    <td><?= htmlspecialchars($sarana['kategori']) ?></td>
                    <td><?= htmlspecialchars($sarana['status']) ?></td>
                    <td><?= htmlspecialchars($sarana['tanggal_pengadaan']) ?></td>
                    <td><?= htmlspecialchars($sarana['tanggal_pemeliharaan']) ?></td>
                    <td><?= htmlspecialchars($sarana['jumlah']) ?></td>
                    <td><?= htmlspecialchars($sarana['keterangan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-button">← Kembali ke Dashboard</a>
</div>

</body>
</html>

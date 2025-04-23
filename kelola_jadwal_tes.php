<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");

// Ambil data user saat ini
$username = $_SESSION['username'];
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    echo "Anda tidak memiliki hak akses.";
    exit;
}

// Menambahkan jadwal tes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_tes = $_POST['tanggal_tes'];
    $waktu_tes = $_POST['waktu_tes'];
    $lokasi_tes = $_POST['lokasi_tes'];
    $status_tes = $_POST['status_tes'];
    $id_petugas = $_POST['id_petugas'];

    // Simpan ke tabel jadwal_tes
    $stmtInsert = $koneksi->prepare("INSERT INTO jadwal_tes (tanggal_tes, waktu_tes, lokasi_tes, status_tes, id_petugas) VALUES (?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssi", $tanggal_tes, $waktu_tes, $lokasi_tes, $status_tes, $id_petugas);
    $stmtInsert->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil semua jadwal tes yang ada
$jadwal_result = $koneksi->query("SELECT * FROM jadwal_tes");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Jadwal Tes PMB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        input, button, select {
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219150;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        .back-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #1d6fa5;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container">
    <!-- Tombol Kembali ke Dashboard -->
    <a href="dashboard.php" class="back-button">← Kembali ke Dashboard</a>

    <h1>Kelola Jadwal Tes PMB</h1>

    <h2>Tambah Jadwal Tes</h2>
    <form method="POST">
        <input type="date" name="tanggal_tes" placeholder="Tanggal Tes" required>
        <input type="time" name="waktu_tes" placeholder="Waktu Tes" required>
        <input type="text" name="lokasi_tes" placeholder="Lokasi Tes" required>
        <select name="status_tes" required>
            <option value="aktif">Aktif</option>
            <option value="tidak aktif">Tidak Aktif</option>
        </select>
        <input type="number" name="id_petugas" placeholder="ID Petugas" required>
        <button type="submit">Simpan Jadwal Tes</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Tanggal Tes</th>
                <th>Waktu Tes</th>
                <th>Lokasi Tes</th>
                <th>Status Tes</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($jadwal = $jadwal_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($jadwal['tanggal_tes']) ?></td>
                    <td><?= htmlspecialchars($jadwal['waktu_tes']) ?></td>
                    <td><?= htmlspecialchars($jadwal['lokasi_tes']) ?></td>
                    <td><?= htmlspecialchars($jadwal['status_tes']) ?></td>
                    <td><?= htmlspecialchars($jadwal['id_petugas']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

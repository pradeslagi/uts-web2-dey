<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");

$username = $_SESSION['username'];

// Ambil data user dari database
$stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

$user = $result->fetch_assoc();
$role = $user['role'];

// Identifikasi role
$is_admin = $role === 'admin';

if (!$is_admin) {
    echo "Anda tidak memiliki hak akses.";
    exit;
}

// Ambil daftar petugas PMB
$query = "SELECT * FROM tb_petugas_pmb";
$petugas_result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Petugas PMB</title>
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
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .button {
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #e67e22;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
            font-weight: bold;
        }

        td a {
            color: #f39c12;
            text-decoration: none;
        }

        td a:hover {
            color: #e67e22;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            background-color: #2ecc71;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }

        .actions a:hover {
            background-color: #27ae60;
        }

        .actions .delete {
            background-color: #e74c3c;
        }

        .actions .delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <div class="container">
        <h1>Daftar Petugas PMB</h1>

        <!-- Tombol Tambah Petugas -->
        <a href="tambah_petugas_pmb.php" class="button">Tambah Petugas PMB</a>

        <!-- Tabel Daftar Petugas -->
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kontak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($petugas = $petugas_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($petugas['nama']) ?></td>
                        <td><?= htmlspecialchars($petugas['jabatan']) ?></td>
                        <td><?= htmlspecialchars($petugas['kontak']) ?></td>
                        <td class="actions">
                            <a href="edit_petugas.php?id=<?= $petugas['id'] ?>">Edit</a>
                            <a href="hapus_petugas.php?id=<?= $petugas['id'] ?>" class="delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

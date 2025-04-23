<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$koneksi = new mysqli("localhost", "root", "", "db_dey");

// Periksa koneksi database
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

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

// Fungsi untuk hapus petugas
if (isset($_GET['hapus_id'])) {
    $id_petugas = $_GET['hapus_id'];
    $stmtHapus = $koneksi->prepare("DELETE FROM tb_users WHERE id = ?");
    $stmtHapus->bind_param("i", $id_petugas);
    $stmtHapus->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fungsi untuk edit petugas
if (isset($_POST['edit_id'])) {
    $id_petugas = $_POST['edit_id'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username_baru = $_POST['username'];
    $password_baru = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmtEdit = $koneksi->prepare("UPDATE tb_users SET nama_lengkap = ?, username = ?, password = ? WHERE id = ?");
    $stmtEdit->bind_param("sssi", $nama_lengkap, $username_baru, $password_baru, $id_petugas);
    $stmtEdit->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Tambah petugas jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit_id'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $username_baru = $_POST['username'];
    $password_baru = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan ke tb_users
    $stmtInsert = $koneksi->prepare("INSERT INTO tb_users (username, password, role, nama_lengkap) VALUES (?, ?, 'petugas', ?)");
    $stmtInsert->bind_param("sss", $username_baru, $password_baru, $nama_lengkap);
    $stmtInsert->execute();

    // Redirect ke halaman yang sama untuk menampilkan data terbaru
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil semua petugas (role = 'petugas')
$petugas_result = $koneksi->query("SELECT * FROM tb_users WHERE role = 'petugas'");

// Periksa apakah query berhasil
if (!$petugas_result) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Petugas PMB</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        input, button {
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

        .aksi {
            text-align: center;
        }

        .aksi button {
            background-color: #f39c12;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .aksi button:hover {
            background-color: #e67e22;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Panah Kembali */
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- Tombol Kembali -->
<a href="dashboard.php" class="back-button">
    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
</a>

<div class="container">
    <h1>Daftar Petugas PMB</h1>

    <h2>Tambah Petugas</h2>
    <form method="POST">
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Simpan Petugas</button>
    </form>

    <h2>Daftar Petugas</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($petugas = $petugas_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($petugas['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($petugas['username']) ?></td>
                    <td class="aksi">
                        <!-- Tombol Edit -->
                        <button onclick="openModal(<?= $petugas['id'] ?>, '<?= htmlspecialchars($petugas['nama_lengkap']) ?>', '<?= htmlspecialchars($petugas['username']) ?>')">Edit</button>

                        <!-- Tombol Hapus -->
                        <a href="?hapus_id=<?= $petugas['id'] ?>" onclick="return confirm('Anda yakin ingin menghapus petugas ini?');">
                            <button>Hapus</button>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit Petugas -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Petugas</h2>
        <form method="POST">
            <input type="hidden" id="edit_id" name="edit_id">
            <input type="text" id="edit_nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
            <input type="text" id="edit_username" name="username" placeholder="Username" required>
            <input type="password" id="edit_password" name="password" placeholder="Password baru" required>
            <button type="submit">Update Petugas</button>
        </form>
    </div>
</div>

<script>
    function openModal(id, nama_lengkap, username) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama_lengkap').value = nama_lengkap;
        document.getElementById('edit_username').value = username;
        document.getElementById('editModal').style.display = "block";
    }

    function closeModal() {
        document.getElementById('editModal').style.display = "none";
    }
</script>

</body>
</html>

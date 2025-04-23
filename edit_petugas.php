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

// Edit petugas jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_petugas = $_POST['id'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username_baru = $_POST['username'];

    // Update data petugas di tb_users
    $stmtUpdate = $koneksi->prepare("UPDATE tb_users SET nama_lengkap = ?, username = ? WHERE id = ?");
    $stmtUpdate->bind_param("ssi", $nama_lengkap, $username_baru, $id_petugas);
    $stmtUpdate->execute();

    header("Location: daftar_petugas.php");
    exit;
}

// Ambil ID petugas yang ingin diedit
$id_petugas = $_GET['id'];
$stmtEdit = $koneksi->prepare("SELECT * FROM tb_users WHERE id = ? AND role = 'petugas'");
$stmtEdit->bind_param("i", $id_petugas);
$stmtEdit->execute();
$petugas = $stmtEdit->get_result()->fetch_assoc();

if (!$petugas) {
    echo "Petugas tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Styling yang sama seperti sebelumnya */
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container">
    <h1>Edit Petugas</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $petugas['id'] ?>">
        <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($petugas['nama_lengkap']) ?>" placeholder="Nama Lengkap" required>
        <input type="text" name="username" value="<?= htmlspecialchars($petugas['username']) ?>" placeholder="Username" required>
        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>

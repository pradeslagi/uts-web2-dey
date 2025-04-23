<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$check_query = $conn->query("SELECT * FROM pendaftar WHERE username='$username'");
$existing_data = $check_query->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $jenis_sekolah = $_POST['jenis_sekolah'];
    $prodi_pilihan = $_POST['prodi_pilihan'];

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $berkas_ijazah = $_FILES['berkas_ijazah']['name'];
    $berkas_kk = $_FILES['berkas_kk']['name'];
    $berkas_foto = $_FILES['berkas_foto']['name'];

    if ($berkas_ijazah) {
        move_uploaded_file($_FILES["berkas_ijazah"]["tmp_name"], $target_dir . $berkas_ijazah);
    } else {
        $berkas_ijazah = $existing_data['berkas_ijazah'] ?? '';
    }

    if ($berkas_kk) {
        move_uploaded_file($_FILES["berkas_kk"]["tmp_name"], $target_dir . $berkas_kk);
    } else {
        $berkas_kk = $existing_data['berkas_kk'] ?? '';
    }

    if ($berkas_foto) {
        move_uploaded_file($_FILES["berkas_foto"]["tmp_name"], $target_dir . $berkas_foto);
    } else {
        $berkas_foto = $existing_data['berkas_foto'] ?? '';
    }

    if ($existing_data) {
        $sql = "UPDATE pendaftar SET
            nama_lengkap='$nama_lengkap',
            email='$email',
            tempat_lahir='$tempat_lahir',
            tanggal_lahir='$tanggal_lahir',
            jenis_kelamin='$jenis_kelamin',
            alamat='$alamat',
            kecamatan='$kecamatan',
            jenis_sekolah='$jenis_sekolah',
            prodi_pilihan='$prodi_pilihan',
            berkas_ijazah='$berkas_ijazah',
            berkas_kk='$berkas_kk',
            berkas_foto='$berkas_foto'
            WHERE username='$username'";
    } else {
        $sql = "INSERT INTO pendaftar (username, nama_lengkap, email, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, kecamatan, jenis_sekolah, prodi_pilihan, berkas_ijazah, berkas_kk, berkas_foto)
            VALUES ('$username', '$nama_lengkap', '$email', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$alamat', '$kecamatan', '$jenis_sekolah', '$prodi_pilihan', '$berkas_ijazah', '$berkas_kk', '$berkas_foto')";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data pendaftaran berhasil disimpan'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $conn->error . "');</script>";
    }
}

$sql_prodi = "SELECT * FROM program_studi";
$prodi_result = $conn->query($sql_prodi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran PMB</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 30px; border-radius: 8px; width: 70%; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #f39c12; text-align: center; }
        form label { font-weight: bold; margin-top: 10px; display: block; }
        form input, form select, form textarea {
            width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background: #f39c12; color: white; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background: #e67e22; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulir Pendaftaran Mahasiswa Baru</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required value="<?= $existing_data['nama_lengkap'] ?? '' ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?= $existing_data['email'] ?? '' ?>">

            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" required value="<?= $existing_data['tempat_lahir'] ?? '' ?>">

            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" required value="<?= $existing_data['tanggal_lahir'] ?? '' ?>">

            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" required>
                <option value="">Pilih</option>
                <option value="Laki-laki" <?= (isset($existing_data['jenis_kelamin']) && $existing_data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= (isset($existing_data['jenis_kelamin']) && $existing_data['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
            </select>

            <label>Alamat</label>
            <textarea name="alamat" required><?= $existing_data['alamat'] ?? '' ?></textarea>

            <label>Kecamatan</label>
            <input type="text" name="kecamatan" required value="<?= $existing_data['kecamatan'] ?? '' ?>">

            <label>Jenis Sekolah</label>
            <input type="text" name="jenis_sekolah" required value="<?= $existing_data['jenis_sekolah'] ?? '' ?>">

            <label>Pilihan Program Studi</label>
            <select name="prodi_pilihan" required>
                <?php while ($row = $prodi_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= (isset($existing_data['prodi_pilihan']) && $existing_data['prodi_pilihan'] == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['nama_prodi'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Upload Ijazah (PDF/JPG)</label>
            <input type="file" name="berkas_ijazah" <?= isset($existing_data['berkas_ijazah']) ? '' : 'required' ?>>

            <label>Upload Kartu Keluarga (PDF/JPG)</label>
            <input type="file" name="berkas_kk" <?= isset($existing_data['berkas_kk']) ? '' : 'required' ?>>

            <label>Upload Foto (3x4)</label>
            <input type="file" name="berkas_foto" <?= isset($existing_data['berkas_foto']) ? '' : 'required' ?>>

            <button type="submit">Kirim Pendaftaran</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>

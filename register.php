<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role     = $_POST['role'];

    // Cek jika username kosong
    if (empty($username)) {
        echo "<div class='alert alert-danger text-center'>❌ Username tidak boleh kosong!</div>";
    } else {
        // Cek apakah username sudah ada
        $stmt_check = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Jika username sudah ada, tampilkan pesan error dan redirect ke halaman login
            echo "<div class='alert alert-danger text-center'>❌ Username sudah terdaftar! Redirecting to login...</div>";
            header("refresh:3;url=login.php"); // Redirect setelah 3 detik
            exit;
        } else {
            // Jika username belum ada, lanjutkan proses registrasi
            $stmt = $koneksi->prepare("INSERT INTO tb_users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;

                // Jika role adalah pendaftar, masukkan ke tabel pendaftar
                if ($role == 'pendaftar') {
                    $nama = $_POST['nama'];
                    $email = $_POST['email'];

                    // Menyimpan data pendaftar
                    $stmt2 = $koneksi->prepare("INSERT INTO pendaftar (user_id, nama_lengkap, email) VALUES (?, ?, ?)");
                    $stmt2->bind_param("iss", $user_id, $nama, $email);
                    $stmt2->execute();
                }

                // Redirect ke halaman login setelah sukses
                header("Location: login.php");
                exit;
            } else {
                // Jika gagal
                echo "<div class='alert alert-danger text-center'>❌ Gagal register!</div>";
            }
        }
    }
}
?>

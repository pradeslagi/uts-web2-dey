<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil username dan password dari form dan sanitasi input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Cek jika username atau password kosong
    if (empty($username) || empty($password)) {
        echo "<div class='alert alert-danger text-center'>❌ Username atau Password tidak boleh kosong!</div>";
    } else {
        // Query untuk mendapatkan data user berdasarkan username
        $stmt = $koneksi->prepare("SELECT * FROM tb_users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Cek apakah password yang dimasukkan sesuai dengan hash password yang ada di database
            if (password_verify($password, $user['password'])) {
                // Set session untuk user yang login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Jika role adalah 'pendaftar', redirect ke pendaftaran.php
                if ($user['role'] == 'pendaftar') {
                    header("Location: pendaftaran.php");
                    exit;
                } else {
                    // Jika bukan pendaftar, arahkan ke dashboard atau halaman lain sesuai role
                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                // Jika password salah
                echo "<div class='alert alert-danger text-center'>❌ Password salah!</div>";
            }
        } else {
            // Jika username tidak ditemukan
            echo "<div class='alert alert-danger text-center'>❌ Username tidak ditemukan!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Pengguna</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdfaf7;
      font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
      background-color: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      margin-top: 80px;
      border: 1px solid #f1e2da;
    }

    .btn-nude {
      background-color: #e0bfb8;
      border-color: #e0bfb8;
      color: white;
    }

    .btn-nude:hover {
      background-color: #d1a7a0;
      border-color: #d1a7a0;
    }

    .text-nude {
      color: #8b5e3c;
    }

    a {
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4 login-container">
      <h3 class="text-center text-nude mb-4">Login Pengguna</h3>
      <form method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-nude w-100">Login</button>
      </form>

      <p class="mt-3 text-center">
        Belum punya akun?
        <a href="register.php" class="text-nude fw-semibold">Daftar di sini</a>
      </p>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

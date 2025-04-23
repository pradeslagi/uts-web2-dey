<?php
session_start();
require_once '../config.php'; // koneksi DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM tb_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Simpan sesi login
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['id_user'] = $user['id_user']; // opsional

            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['login_error'] = "Password salah!";
        }
    } else {
        $_SESSION['login_error'] = "Username tidak ditemukan!";
    }

    header("Location: ../login.php");
    exit;
}
?>

<?php
// config/db.php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database kamu
$password = ""; // Sesuaikan dengan password database kamu
$dbname = "db_dey"; // Sesuaikan dengan nama database kamu

// Membuat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}
?>

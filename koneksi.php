<?php
$koneksi = new mysqli("localhost", "root", "", "db_dey");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>

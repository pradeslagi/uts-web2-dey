<?php
session_start();  // Pastikan session dimulai
?>

<div id="sidebar" style="display:none; position: fixed; top: 0; left: 0; width: 250px; height: 100%; background-color: #333; color: white; padding-top: 60px; z-index: 1000;">
    <?php
    if (isset($_SESSION['role'])) {
        echo '<ul>';
        $role = $_SESSION['role'];

        // Menu untuk Admin
        if ($role == 'admin') {
            echo '<li style="padding: 10px; cursor: pointer; font-size: 18px;" onclick="toggleSubmenu(\'petugas-pmb-submenu\')">Tambah Petugas PMB &#9662;</li>';
            echo '<ul id="petugas-pmb-submenu" style="display:none; list-style-type: none; padding-left: 20px;">
                    <li><a href="tambah_petugas_pmb.php" style="color: white; padding: 10px; display: block;">Tambah Petugas PMB</a></li>
                  </ul>';
            echo '<li><a href="data_sarana.php" style="color: white; padding: 10px; display: block;">Data Sarana</a></li>';
            echo '<li><a href="data_keuangan.php" style="color: white; padding: 10px; display: block;">Data Keuangan</a></li>';
            echo '<li><a href="data_akademik.php" style="color: white; padding: 10px; display: block;">Data Akademik</a></li>';
            echo '<li><a href="pendaftaran.php" style="color: white; padding: 10px; display: block;">Pendaftaran</a></li>';
            echo '<li><a href="kelola_jadwal_tes.php" style="color: white; padding: 10px; display: block;">Jadwal Tes</a></li>';
        }
        // Menu untuk Pendaftar
        elseif ($role == 'pendaftar') {
            echo '<li><a href="pendaftaran.php" style="color: white; padding: 10px; display: block;">Pendaftaran</a></li>';
        }
        // Menu untuk Akademik
        elseif ($role == 'akademik') {
            echo '<li><a href="data_akademik.php" style="color: white; padding: 10px; display: block;">Data Akademik</a></li>';
            echo '<li><a href="data_pendaftar.php" style="color: white; padding: 10px; display: block;">Data PMB</a></li>';
            echo '<li><a href="jadwal_kuliah.php" style="color: white; padding: 10px; display: block;">Jadwal Perkuliahan</a></li>';
            echo '<li><a href="mata_kuliah.php" style="color: white; padding: 10px; display: block;">Mata Kuliah</a></li>';
            echo '<li><a href="nilai_mahasiswa.php" style="color: white; padding: 10px; display: block;">Nilai Akademik</a></li>';
        }
        // Menu untuk Keuangan
        elseif ($role == 'keuangan') {
            echo '<li><a href="data_keuangan.php" style="color: white; padding: 10px; display: block;">Data Keuangan</a></li>';
        }
        // Menu untuk Sarana
        elseif ($role == 'sarana') {
            echo '<li><a href="data_sarana.php" style="color: white; padding: 10px; display: block;">Data Sarana</a></li>';
            echo '<li><a href="kelola_sarana_pemeliharaan.php" style="color: white; padding: 10px; display: block;">Data Pemeliharaan</a></li>';
        }
        // Menu untuk Petugas
        elseif ($role == 'petugas') {
            echo '<li><a href="data_akademik.php" style="color: white; padding: 10px; display: block;">Data Akademik</a></li>';
            echo '<li><a href="data_pendaftar.php" style="color: white; padding: 10px; display: block;">Data PMB</a></li>';
        }

        echo '<li><a href="logout.php" style="color: white; padding: 10px; display: block;">Logout</a></li>';
        echo '</ul>';
    }
    ?>
</div>

<!-- Toggle Hamburger -->
<div id="hamburger" style="position: fixed; top: 20px; left: 20px; cursor: pointer; font-size: 30px; color: #333;">
    &#9776; <!-- Hamburger Icon -->
</div>

<script>
    // Toggle Sidebar
    document.getElementById("hamburger").onclick = function() {
        var sidebar = document.getElementById("sidebar");
        sidebar.style.display = (sidebar.style.display === "none" || sidebar.style.display === "") ? "block" : "none";
    };

    // Toggle submenu
    function toggleSubmenu(menuId) {
        var submenu = document.getElementById(menuId);
        submenu.style.display = (submenu.style.display === "none" || submenu.style.display === "") ? "block" : "none";
    }
</script>

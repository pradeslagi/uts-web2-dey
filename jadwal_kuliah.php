<?php
$conn = new mysqli("localhost", "root", "", "db_dey");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT jk.*, mk.nama_mata_kuliah FROM jadwal_kuliah jk
        JOIN mata_kuliah mk ON jk.id_mata_kuliah = mk.id_mata_kuliah";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Ikon FontAwesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f39c12;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .btn-back i {
            margin-right: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

    <div class="container">
        <h2>Jadwal Kuliah</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Mata Kuliah</th>
                    <th>Hari</th>
                    <th>Jam Mulai</th>
                    <th>Jam Selesai</th>
                    <th>Dosen Pengampu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_mata_kuliah']) ?></td>
                        <td><?= htmlspecialchars($row['hari']) ?></td>
                        <td><?= htmlspecialchars($row['jam_mulai']) ?></td>
                        <td><?= htmlspecialchars($row['jam_selesai']) ?></td>
                        <td><?= htmlspecialchars($row['dosen_pengampu']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php $conn->close(); ?>

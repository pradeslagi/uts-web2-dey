<?php
$users = [
    ['username' => 'admin',    'password' => '111',   'role' => 'admin'],
    ['username' => 'akademik', 'password' => 'akd',   'role' => 'akademik'],
    ['username' => 'keuangan', 'password' => 'keu',   'role' => 'keuangan'],
    ['username' => 'sarana',   'password' => 'sar',   'role' => 'sarana'],
];

foreach ($users as $user) {
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    echo "INSERT INTO tb_users (username, password, role) VALUES (";
    echo "'" . $user['username'] . "', ";
    echo "'" . $hashed_password . "', ";
    echo "'" . $user['role'] . "'";
    echo ");<br><br>";
}
?>

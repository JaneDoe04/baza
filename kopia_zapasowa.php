<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ksiegowosc";

try {
    $polaczenie = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $polaczenie->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
$backupFile = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = "mysqldump --user={$username} --password={$password} --host={$servername} {$dbname} > {$backupFile}";

system($command, $output);

if ($output === 0) {
    echo "Backup created successfully.";
} else {
    echo "Backup creation failed.";
}
$polaczenie = null; 
?>
<?php
include('testFunctions.php');

$title = 'Backup';
$backup_file = 'database_backup_' . date('Y-m-d_H-i-s') . '.sql';
$backup_path = 'D:\laragon\www\ucua\SQL';

if (!is_dir($backup_path)) {
    mkdir($backup_path, 0755, true);
}

$backup_query = 'mysqldump -u ' . escapeshellarg($username) . ' -p' . escapeshellarg($password) . ' ' . escapeshellarg($database) . ' > ' . escapeshellarg($backup_path . '/' . $backup_file);

exec($backup_query, $output, $return_var);

$db->close();

if ($return_var === 0) {
    echo 'Database backup created successfully! File: ' . $backup_file;
} else {
    echo 'Failed to create database backup. Please check your configuration.';
}

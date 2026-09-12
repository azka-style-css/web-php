<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'shop';

$connection = mysqli_connect($host, $user, $pass, $dbname);

if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

return $connection;

<?php
$host = 'localhost';
$user = 'root'; // Default user for XAMPP
$password = ''; // Leave it blank by default in XAMPP
$db_name = 'test';

$conn = mysqli_connect($host, $user, $password, $db_name);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

<?php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'manageease';

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("connection failed" . mysqli_connect_error());
}

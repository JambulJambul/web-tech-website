<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'wt_project';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    echo "Connection failed";
    exit();
}

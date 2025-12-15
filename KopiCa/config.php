<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kopica";

$koneksi = mysqli_connect($host, $user, $pass, $db);
$conn = $koneksi; 

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

if(!session_id()) session_start();
?>
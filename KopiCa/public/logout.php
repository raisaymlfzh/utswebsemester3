<?php
include '../config.php';
session_destroy();
echo "<script>alert('Anda telah logout'); window.location='../public/login.php';</script>";

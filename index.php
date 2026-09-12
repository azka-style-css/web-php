<?php
// index.php - Entry point aplikasi

// Cek apakah user sudah login (dari session)
session_start();

if (isset($_SESSION['user'])) {
    header("Location: app/pages/dashboard.php");
    exit;
} else {
    header("Location: app/pages/login.php");
    exit;
}
?>
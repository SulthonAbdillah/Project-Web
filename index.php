<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.html");
    exit(); // Pastikan untuk keluar setelah melakukan redirect
}

// Jika sudah login, lanjutkan ke halaman utama
header("Location: index.html");
exit(); // Pastikan untuk keluar setelah melakukan redirect
?>

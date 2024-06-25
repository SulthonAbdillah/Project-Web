<?php
session_start();
include 'konek.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencegah SQL Injection
    $username = $conn->real_escape_string($username);

    // Membuat query untuk memeriksa pengguna
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // Langsung membandingkan password
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Pengguna ditemukan, set session
        $_SESSION['username'] = $username;
        // Redirect ke halaman utama
        header("Location: index.html");
        exit();
    } else {
        // Jika login gagal, kembali ke halaman login dengan pesan kesalahan
        header("Location: login.html?error=Username atau password salah!");
        exit();
    }
} else {
    // Jika tidak menggunakan metode POST, kembali ke halaman login
    header("Location: login.html");
    exit();
}
?>

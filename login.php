<?php
include 'konek.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencegah SQL Injection
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Membuat query untuk memeriksa pengguna
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        // Jika login berhasil, arahkan ke halaman index.html
        header("Location: index.html");
    } else {
        // Jika login gagal, kembali ke halaman login dengan pesan kesalahan
        header("Location: login.html?error=Username atau password salah!");
    }
}
?>

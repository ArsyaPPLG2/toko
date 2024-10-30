<?php
$servername = "localhost";  // Atau bisa gunakan 127.0.0.1
$username = "root";         // Username database MySQL
$password = "";             // Password MySQL (kosong jika default XAMPP)
$dbname = "tokookyu";       // Nama database yang sudah dibuat

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

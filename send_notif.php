<?php
session_start();
// Koneksi ke database
$host = "localhost";
$user = "root"; // Sesuaikan dengan username database kamu
$pass = ""; // Sesuaikan dengan password database kamu
$dbname = "tokookyu"; // Sesuaikan dengan nama database kamu

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


// Cek apakah form untuk mengirim notifikasi telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dapatkan data dari form
    $user_id = $_POST['user_id']; // ID pengguna yang akan menerima notifikasi
    $status = $_POST['status']; // Status pesanan (misalnya: 'sedang diproses' atau 'sampai')
    $message = $_POST['message']; // Pesan notifikasi

    // Simpan notifikasi dalam sesi
    if (!isset($_SESSION['notifications'])) {
        $_SESSION['notifications'] = []; // Inisialisasi array jika belum ada
    }

    $_SESSION['notifications'][] = [
        'type' => ($status == 'sampai') ? 'success' : 'info', // Tipe notifikasi
        'title' => "Pesanan Anda", // Judul notifikasi
        'message' => $message // Isi pesan
    ];

    // Mengembalikan respon JSON
    echo json_encode(['status' => 'success', 'message' => 'Notifikasi berhasil dikirim.']);
    exit();
}

?>

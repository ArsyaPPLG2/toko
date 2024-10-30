<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login setelah logout
header("Location: home.php"); // Sesuaikan dengan halaman login Anda
exit();
?>

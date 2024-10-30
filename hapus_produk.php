<?php
// Koneksi ke database
$host = "localhost";
$user = "root"; // Sesuaikan dengan username database kamu
$pass = ""; // Sesuaikan dengan password database kamu
$dbname = "tokookyu"; // Sesuaikan dengan nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi ID
if (!empty($id) && is_numeric($id)) {
    // Menyiapkan query untuk menghapus data
    $sql = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, tampilkan pesan sukses
            echo "Produk berhasil dihapus.";
        } else {
            // Jika ada kesalahan saat eksekusi
            echo "Error saat menghapus produk: " . $stmt->error;
        }

        // Menutup statement
        $stmt->close();
    } else {
        // Jika ada kesalahan saat persiapan statement
        echo "Error dalam persiapan statement: " . $conn->error;
    }
} else {
    // Jika ID tidak valid
    echo "ID tidak valid.";
}

// Menutup koneksi
$conn->close();

// Mengalihkan kembali ke halaman detail_produk.php setelah 2 detik
header("refresh:2; url=detail_produk.php");
?>

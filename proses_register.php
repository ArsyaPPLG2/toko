<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'tokookyu');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek jika data dikirim
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

    // Siapkan dan bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");

    // Cek apakah statement berhasil dipersiapkan
    if ($stmt) {
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        // Eksekusi statement dan cek hasilnya
        if ($stmt->execute()) {
            echo "User registered successfully!";
            header("Location: login.php"); // Arahkan ke halaman login setelah berhasil registrasi
            exit();
        } else {
            echo "Error: " . $stmt->error; // Pesan jika terjadi kesalahan
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error; // Tampilkan error dari koneksi
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "All fields are required."; // Pesan jika data tidak lengkap
}
?>

<?php
session_start();

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'tokookyu');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah data dikirim dan tidak kosong
if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Query untuk mengambil password dan role
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Cek apakah pengguna ditemukan
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password, $role);
            $stmt->fetch();

            // Verifikasi password
            if (password_verify($password, $hashed_password)) {
                // Regenerasi session ID untuk keamanan
                session_regenerate_id(true);

                // Simpan informasi pengguna di session
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect berdasarkan role
                if ($role === 'admin') {
                    header("Location: admin.php");
                } elseif ($role === 'user') {
                    header("Location: home.php");
                } else {
                    echo "Role tidak dikenal.";
                }
                exit();
            } else {
                echo "Password salah.";
            }
        } else {
            echo "Username atau password salah.";
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "Error pada query: " . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "Semua kolom harus diisi.";
}
?>

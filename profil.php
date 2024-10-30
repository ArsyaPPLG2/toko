<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'tokookyu');

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data pengguna berdasarkan username
$username = $_SESSION['username'];
$query = "SELECT id, username, name, birthdate, email, phone, profile_picture FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Cek jika tombol simpan ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Proses upload foto profil jika ada
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_picture);

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $updateQuery = "UPDATE users SET name=?, birthdate=?, email=?, phone=?, profile_picture=? WHERE username=?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ssssss", $name, $birthdate, $email, $phone, $profile_picture, $username);
        } else {
            echo "Gagal mengupload foto.";
            exit();
        }
    } else {
        $updateQuery = "UPDATE users SET name=?, birthdate=?, email=?, phone=? WHERE username=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssss", $name, $birthdate, $email, $phone, $username);
    }

    if ($stmt->execute()) {
        header("Location: profil.php");
        exit();
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <style>
        html, body {
            
            margin: 0;
            height: 100%; /* Ensure the body takes the full height */
            font-family: Arial, sans-serif;
        }
        .wrapper {
            
            display: flex; /* Use flexbox for layout */
            height: 100%; /* Full height for wrapper */
        }
        .sidebar {
            
            width: 250px;
            background-color: #a084ca;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            display: block;
            margin: 10px 0;
            font-size: 18px;
        }
        .sidebar a:hover {
            color: #d4a5ff;
        }
        .container {
            
            width: 100%;
            height: 100%;
            flex: 1; /* Allow container to fill the remaining space */
            padding: 20px;
            overflow-y: auto; /* Allow scrolling if content is too long */
        }
        .profile-wrapper {
            
            background-color: #e7e7ff;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-wrapper img {
            
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .profile-info {
            
            flex: 1;
        }
        input[type="text"], input[type="email"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #6a0dad;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #5c0ba1;
        }
        
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
    <button onclick="history.back()">←</button>
        <a href="home.php"><h2>TOKOOKYU</h2></a>
        <p><?php echo $user['username']; ?></p>
        <a href="#">Saldo Saya</a>
        <a href="#">Favorit</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <div class="profile-wrapper">
            <div>
                <?php if ($user['profile_picture']): ?>
                    <img src="uploads/<?php echo $user['profile_picture']; ?>" alt="Foto Profil">
                <?php else: ?>
                    <img src="uploads/default.png" alt="Foto Default">
                <?php endif; ?>
                <form action="profil.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_picture">
            </div>

            <div class="profile-info">
                <label>Nama:</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

                <label>Tanggal Lahir:</label>
                <input type="date" name="birthdate" value="<?php echo $user['birthdate']; ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label>No. Telepon:</label>
                <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>

                <button type="submit" class="btn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>

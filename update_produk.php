<?php
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

// Cek apakah ada id produk yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID
    if (!empty($id) && is_numeric($id)) {
        // Ambil data produk berdasarkan ID
        $sql = "SELECT name, deskripsi, harga, foto FROM product WHERE id_produk = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Jika data ditemukan
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $nama = $product['name'];
                $deskripsi = $product['deskripsi'];
                $harga = $product['harga'];
                $foto = $product['foto'];
            } else {
                echo "Produk tidak ditemukan!";
                exit;
            }

            $stmt->close();
        } else {
            echo "Error dalam persiapan statement: " . $conn->error;
        }
    } else {
        echo "ID tidak valid.";
        exit;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $uploadOk = 1;

    // Cek jika ada file foto yang diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/"; // Folder tempat menyimpan foto
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi apakah file yang diunggah adalah gambar
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        // Cek apakah file sudah ada
        if (file_exists($target_file)) {
            echo "Maaf, file sudah ada.";
            $uploadOk = 0;
        }

        // Batasi tipe file yang boleh diunggah
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Hanya file JPG, JPEG, dan PNG yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Cek ukuran file (maksimal 5MB)
        if ($_FILES["foto"]["size"] > 5000000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Jika $uploadOk == 0, gagal mengunggah
        if ($uploadOk == 0) {
            echo "Maaf, file tidak berhasil diunggah.";
        } else {
            // Jika semua pengecekan lolos, coba unggah file
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $foto = basename($_FILES["foto"]["name"]); // Simpan nama file foto ke database
            } else {
                echo "Terjadi kesalahan saat mengunggah file.";
            }
        }
    }

    // Validasi data yang diinput
    if (!empty($nama) && !empty($deskripsi) && !empty($harga) && is_numeric($harga) && $uploadOk) {
        // Query untuk memperbarui data produk
        $sql = "UPDATE product SET name = ?, deskripsi = ?, harga = ?, foto = ? WHERE id_produk = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssdsi", $nama, $deskripsi, $harga, $foto, $id);

            // Eksekusi query
            if ($stmt->execute()) {
                echo "Produk berhasil diperbarui.";
                header("Location: detail_produk.php"); // Redirect ke halaman detail produk
                exit;
            } else {
                echo "Error saat memperbarui produk: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error dalam persiapan statement: " . $conn->error;
        }
    } else {
        echo "Harap isi semua kolom dengan benar.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .btn-foto {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-foto:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
            
            <label for="deskripsi">Deskripsi</label>
            <input type="text" name="deskripsi" id="deskripsi" value="<?php echo htmlspecialchars($deskripsi); ?>" required>
            
            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" value="<?php echo htmlspecialchars($harga); ?>" required>

            <label for="foto">Foto</label>
            <input type="file" name="foto" id="foto">

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>

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

// Proses form saat tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $produk = $_POST['produk'];
    $jumlah = $_POST['jumlah'];
    
    // Nilai default (jika kolom tidak diinput melalui form)
    $deskripsi = "Deskripsi produk"; // Ubah sesuai keinginan
    $harga = 0; // Ubah jika ingin memasukkan harga dari form

    // Proses upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];
        
        // Tentukan lokasi penyimpanan file
        $upload_dir = 'uploads/'; // Pastikan folder ini ada dan dapat ditulis
        if (move_uploaded_file($tmp_name, $upload_dir . $foto)) {
            $foto_path = $upload_dir . $foto;
        } else {
            echo "Gagal mengunggah foto.";
            exit();
        }
    } else {
        $foto_path = NULL; // Jika tidak ada foto, set menjadi NULL atau default
    }

    // Insert data ke tabel admin
    $sql_admin = "INSERT INTO admin (id, nama, produk, jumlah, foto) VALUES ('$id', '$nama', '$produk', '$jumlah', '$foto_path')";
    
    if ($conn->query($sql_admin) === TRUE) {
        // Insert data ke tabel product
        $sql_product = "INSERT INTO product (id_produk, name, deskripsi, harga, qty, foto) VALUES ('$id', '$nama', '$deskripsi', '$harga', '$jumlah', '$foto_path')";

        if ($conn->query($sql_product) === TRUE) {
            echo "Data berhasil ditambahkan ke tabel admin dan product!";
            // Redirect ke halaman admin setelah berhasil menambah data
            header("Location: admin.php");
            exit();
        } else {
            echo "Error saat menambahkan data ke tabel product: " . $conn->error;
        }
    } else {
        echo "Error saat menambahkan data ke tabel admin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Barang</h2>
        <form action="tambah_produk.php" method="POST" enctype="multipart/form-data">
            <label for="id">Id:</label>
            <input type="text" id="id" name="id" required>

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="produk">Produk:</label>
            <input type="text" id="produk" name="produk" required>

            <label for="jumlah">Jumlah:</label>
            <input type="number" id="jumlah" name="jumlah" required>

            <label for="foto">Foto</label>
            <input type="file" name="foto" id="foto" accept="image/*">

            <button type="submit" class="submit-btn">Tambah Barang</button>
        </form>
    </div>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>

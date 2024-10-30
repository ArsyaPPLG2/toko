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

// Ambil data dari tabel produk
$sql = "SELECT id, nama, produk, jumlah FROM admin"; // Ganti sesuai nama tabel kamu
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        a {
            color: black;
            text-decoration: none;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            text-align: center;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        .detail-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .detail-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat datang di Halaman Admin</h2>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result === false) {
                    echo "Error: " . $conn->error;
                } else if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['produk'] . "</td>";
                        echo "<td>" . $row['jumlah'] . "</td>";
                        echo "<td><a href='detail_produk.php?id=" . $row['id'] . "' class='detail-btn'>Detail</a></td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Tautan ke halaman pengiriman notifikasi -->
        <a href="pesan_admin.php" class="add-btn">Kirim Notifikasi kepada Pengguna</a>
    </div>
    <a href="tambah_produk.php" class="add-btn">Tambah Barang</a>
</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>

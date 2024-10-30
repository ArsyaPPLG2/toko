<?php
session_start(); // Memulai sesi

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

// Mendapatkan ID produk dari URL
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Query untuk mengambil detail produk berdasarkan ID
    $sql = "SELECT * FROM product WHERE id_produk = '$id_produk'";
    $result = $conn->query($sql);

    // Memeriksa apakah produk ditemukan
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Produk tidak ditemukan.";
        exit();
    }
} else {
    echo "ID produk tidak tersedia.";
    exit();
}

// Mengatur jumlah produk dalam sesi
if (!isset($_SESSION['quantity'])) {
    $_SESSION['quantity'] = 0; // Default quantity
}

// Mengurangi jumlah produk saat tombol "+" atau "-" ditekan
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add' && $_SESSION['quantity'] < $product['qty']) {
        $_SESSION['quantity']++;
    } elseif ($_POST['action'] == 'subtract' && $_SESSION['quantity'] > 0) {
        $_SESSION['quantity']--;
    }
}

// Menambahkan produk ke keranjang
if (isset($_POST['add_to_cart'])) {
    // Memastikan sesi keranjang sudah ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Inisialisasi keranjang
    }

    // Menyimpan produk ke dalam keranjang
    $product_id = $product['id_produk'];
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['harga'],
        'quantity' => $_SESSION['quantity'], // jumlah yang dipilih
        'stock' => $product['qty']
    ];

    // Reset quantity setelah ditambahkan ke keranjang
    $_SESSION['quantity'] = 0;

    // Redirect ke halaman keranjang
    header("Location: cart.php");
    exit();
}

// Menambahkan produk ke keranjang dan langsung ke checkout
if (isset($_POST['buy_now'])) {
    // Memastikan sesi keranjang sudah ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Inisialisasi keranjang
    }

    // Menyimpan produk ke dalam keranjang
    $product_id = $product['id_produk'];
    $_SESSION['cart'][$product_id] = [
        'name' => $product['name'],
        'price' => $product['harga'],
        'quantity' => $_SESSION['quantity'], // jumlah yang dipilih
        'stock' => $product['qty']
    ];

    // Redirect ke halaman checkout
    header("Location: checkout.php");
    exit();
}

$stok = $product['qty'] - $_SESSION['quantity']; // Update stok yang tersisa
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .product-detail {
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .product-detail img {
            width: 100%;
            border-radius: 10px;
        }
        .product-text {
            text-align: center;
            margin: 20px 0;
        }
        .order-section, .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
        }
        .quantity-control button {
            width: 30px;
            height: 30px;
            font-size: 18px;
            background-color: #ccc;
            border: none;
            cursor: pointer;
            margin: 0 5px;
            border-radius: 5px;
        }
        .action-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .buy-now-btn {
            background-color: #4CAF50;
            color: white;
        }
        .add-to-cart-btn {
            background-color: #ffa500;
            color: white;
        }
    </style>
</head>
<body>
   
    <div class="product-detail">

        <button onclick="history.back()">←</button>
        
        <h2>TOKOOKYU</h2>

        <div class="product-info">
            <img src="<?php echo $product['foto']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
            <div class="product-text">
                <h3><?php echo $product['name']; ?></h3>
                <p>Terjual: <?php echo $product['qty']; ?> • ⭐ 4.9 (7 rating)</p>
                <p class="price">RP. <?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['deskripsi']); ?></p> <!-- Menambahkan deskripsi di sini -->
            </div>
        </div>

        <form method="POST" action="">
            <div class="order-section">
                <div class="quantity-control">
                    <button name="action" value="subtract">-</button>
                    <span class="quantity"><?php echo $_SESSION['quantity']; ?></span>
                    <button name="action" value="add">+</button>
                </div>
                <p>Stok: <span class="stock"><?php echo $stok; ?></span></p>
                <p>Subtotal: <span class="subtotal">Rp. <?php echo number_format($product['harga'] * $_SESSION['quantity'], 0, ',', '.'); ?></span></p>
            </div>

            <div class="action-buttons">
                <button type="submit" name="buy_now" class="buy-now-btn">Beli sekarang</button>
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">+ Keranjang</button>
            </div>
        </form>
    </div>

<?php
// Tutup koneksi
$conn->close();
?>
</body>
</html>

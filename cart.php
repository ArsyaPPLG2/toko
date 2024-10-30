<?php
session_start(); // Memulai sesi

// Jika keranjang kosong, tampilkan pesan
if (empty($_SESSION['cart'])) {
    echo "<h2>Keranjang Anda Kosong</h2>";
    echo '<a href="home.php">Kembali ke Beranda</a>';
    exit();
}

// Proses penghapusan item dari keranjang
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]); // Hapus item dari keranjang

    // Redirect untuk mencegah pengiriman ulang form
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .cart-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .cart-item h3 {
            margin: 0;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .action-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
        }
        .remove-button {
            background-color: #000; /* Merah untuk tombol hapus */
            text-decoration:none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h2>Keranjang Belanja Anda</h2>

        <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
            <div class="cart-item">
                <div>
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Harga: Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <p>Jumlah: <?php echo $product['quantity']; ?></p>
                </div>
                <div>
                    <p>Total: Rp. <?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?></p>
                    <a href="cart.php?remove=<?php echo $product_id; ?>" class="remove-button">Hapus</a>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="action-buttons">
            <button onclick="window.location.href='checkout.php'">Checkout</button>
            <button onclick="window.location.href='home.php'">Kembali</button>
        </div>
    </div>
</body>
</html>

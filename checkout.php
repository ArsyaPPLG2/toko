<?php
session_start(); // Memulai sesi

// Jika keranjang kosong, redirect ke halaman keranjang
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.'); window.location.href='home.php';</script>";
    exit();
}

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simpan informasi pembelian ke dalam riwayat
    if (!isset($_SESSION['purchase_history'])) {
        $_SESSION['purchase_history'] = []; // Inisialisasi jika belum ada
    }

    foreach ($_SESSION['cart'] as $product) {
        $_SESSION['purchase_history'][] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $product['quantity']
        ];
    }

    // Hapus keranjang setelah checkout
    unset($_SESSION['cart']);

    // Notifikasi sukses
    echo "<script>alert('Pembayaran berhasil! Terima kasih telah berbelanja.'); window.location.href='home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .checkout-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .checkout-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .checkout-item h3 {
            margin: 0;
        }
        .action-buttons {
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
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>

        <?php foreach ($_SESSION['cart'] as $product): ?>
            <div class="checkout-item">
                <div>
                    <h3><?php echo $product['name']; ?></h3>
                    <p>Harga: Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <p>Jumlah: <?php echo $product['quantity']; ?></p>
                </div>
                <div>
                    <p>Total: Rp. <?php echo number_format($product['price'] * $product['quantity'], 0, ',', '.'); ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <form method="POST" action="">
            <div class="action-buttons">
                <button type="submit">Selesaikan Pembayaran</button>
                <button onclick="window.location.href='cart.php'" type="button">Kembali ke Keranjang</button>
            </div>
        </form>
    </div>
</body>
</html>

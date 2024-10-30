
<?php
// Start session
session_start();

// Koneksi ke database
$host = "localhost";
$user = "root";  // Sesuaikan dengan konfigurasi database Anda
$pass = "";      // Sesuaikan dengan password database Anda
$dbname = "tokookyu";  // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil semua data produk dari tabel `product`
$sql = "SELECT id_produk, name, deskripsi, harga, foto FROM product";
$result = $conn->query($sql);

// Simulate user login status (this would be dynamic in a real-world app)
$loggedIn = isset($_SESSION['username']);  // Dynamically check if a user is logged in

// Simulated username
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";  // If not logged in, show "Guest"
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOKOOKYU - Home</title>
    <style>
        /* CSS Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        a {
            color: black;
            text-decoration: none;
        }
        .header {
        background: linear-gradient(135deg, #6c3ea4, #b76bb3); /* Gradient background */
        color: white;
        padding: 15px 20px; /* Padding yang sedikit lebih besar */
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Bayangan untuk efek kedalaman */
        transition: background 0.3s ease; /* Transisi halus untuk perubahan latar belakang */
        }

        .header:hover {
        background: linear-gradient(135deg, #5b2b91, #a96f94); /* Gradient saat hover */
        }

        .profile {
            display: flex;
            align-items: center;
        }
        .profile span {
            margin-left: 10px;
            font-weight: bold;
            color: #c9c9f0;
        }
        .sidebar-menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 100px; /* Memperbesar lebar sidebar */
            height: 100%;
            background-color: #fff;
            display: none; /* Start hidden */
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
            box-shadow: -2px 0 5px rgba(0,0,0,0.5); /* Tambahkan bayangan */
            transition: right 0.3s; /* Smooth transition */
        }
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .products {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .product {
            background-color: white;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 200px;
            margin: 10px;
        }
        .product img {
            width: 100%;
            border-radius: 5px;
        }
        .product p {
            margin: 10px 0;
            font-weight: bold;
        }

        /* Media Query untuk layar yang lebih kecil */
        @media (max-width: 768px) {
            .products {
                flex-direction: column;
                align-items: center;
            }

            .product {
                width: 90%;
            }

            .sidebar-menu {
                width: 150px; /* Sesuaikan untuk tampilan kecil */
            }
        }

        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile {
                margin-top: 10px;
            }

            .products {
                flex-direction: column;
                align-items: center;
            }

            .product {
                width: 100%;
                margin: 10px 0;
            }

            .sidebar-menu {
                width: 100%;
                height: auto;
                flex-direction: row;
                justify-content: space-around;
            }

            .menu-item {
                margin-bottom: 0;
            }
        }

        footer {
            background: linear-gradient(135deg, #6c3ea4, #b76bb3); /* Gradient background */
            color: white;
            padding: 15px 20px; /* Padding yang sedikit lebih besar */
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Bayangan untuk efek kedalaman */
            transition: background 0.3s ease; /* Transisi halus untuk perubahan latar belakang */
            margin-top: auto; /* Memindahkan footer ke bagian bawah */
        }

        footer:hover {
            background: linear-gradient(135deg, #5b2b91, #a96f94); /* Gradient saat hover */
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-menu');
            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "flex"; // Show sidebar
            } else {
                sidebar.style.display = "none"; // Hide sidebar
            }
        }
    </script>
</head>
<body>

<div class="header">
    <div class="profile">
        <?php if ($loggedIn): ?>
            <a href="profil.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
                <span><?php echo htmlspecialchars($username); ?></span>
            </a>
        <?php else: ?>
            <a href="login.php" style="color: white; text-decoration: none; font-weight: bold;">LOGIN</a>
        <?php endif; ?>
    </div>

    <button onclick="toggleSidebar()" style="background: none; border: none; color: white;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
        </svg>
    </button>
</div>

<!-- Sidebar Menu -->
<?php if ($loggedIn): ?>
    <div class="sidebar-menu">
        
        <div class="menu-item">
            <a href="cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-dash" viewBox="0 0 16 16">
                    <path d="M6.5 7a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1z"/>
                    <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                </svg>
            </a>
            <p>Cart</p>
        </div>
        <div class="menu-item">
            <a href="history.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/>
                </svg>
            </a>
            <p>History</p>
        </div>
        <div class="menu-item">
            <a href="notif.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.273a.5.5 0 0 0 .464.685h9.316a.5.5 0 0 0 .464-.685 13.522 13.522 0 0 1-.663-2.273C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92L8 1.918zM14 12.098A14.533 14.533 0 0 0 14 12H2c0 .035-.002.07-.002.098H14z"/>
                </svg>
            </a>
            <p>Notif</p>
        </div>
        
        <div class="menu-item">
            <a href="help.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-octagon" viewBox="0 0 16 16">
  <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
</svg></a>
            <p>Help</p>
        </div>
        
        <!-- Logout Button -->
        <div class="logout-button">
            <form action="logout.php" method="POST">
                <button type="submit">Log out</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Display Products -->
<div class="products">
    <?php
    // Menampilkan daftar produk
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            echo '<img src="uploads/' . htmlspecialchars($row['foto']) . '" alt="Foto Produk">';
            echo '<p>' . htmlspecialchars($row['name']) . '</p>';
            echo '<p>RP. ' . number_format($row['harga'], 0, ',', '.') . '</p>';
            if ($loggedIn) {
                // Jika sudah login, link mengarah ke halaman detail produk
                echo '<button><a href="detail_produk_home.php?id=' . urlencode($row['id_produk']) . '">Detail</a></button>';
            } else {
                // Jika belum login, link mengarah ke halaman login
                echo '<button><a href="login.php" onclick="alert(\'Silakan login terlebih dahulu untuk melihat detail produk.\')">Detail</a></button>';
            }
            echo '</div>';
        }
    } else {
        echo "<p>Tidak ada produk yang tersedia.</p>";
    }
    ?>
</div>

<script>
        let sidebarVisible = false;

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-menu');
            sidebar.style.display = sidebarVisible ? "none" : "flex"; // Show or hide sidebar
            sidebarVisible = !sidebarVisible; // Toggle visibility state
        }

        function closeSidebar(event) {
            const sidebar = document.querySelector('.sidebar-menu');
            // Check if the click is outside the sidebar and the button
            if (sidebarVisible && !sidebar.contains(event.target) && !event.target.closest('.header button')) {
                sidebar.style.display = "none"; // Hide sidebar
                sidebarVisible = false; // Update visibility state
            }
        }

        document.addEventListener('click', closeSidebar);
    </script>

<footer><p>&copy; 2024 TOKOOKYU. Semua hak dilindungi.</p></footer>
</body>
</html>
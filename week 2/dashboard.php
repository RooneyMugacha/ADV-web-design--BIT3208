<?php
// dashboard.php (public version)
// No session or database required – show static placeholders
$totalCars = 0;
$totalUsers = 0;
$newOrders = 0;
$revenue = 0.00;
$cars = [];
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – Car Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Dashboard specific layout */
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f9f9f9;
        }
        header {
            width: 100%;
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-container {
            font-size: 1.5rem;
            font-weight: bold;
            color: #222;
        }
        .cart-bell {
            position: relative;
            font-size: 1.8rem;
            cursor: pointer;
        }
        .cart-bell span {
            position: absolute;
            top: -8px;
            right: -12px;
            background: #d8456b;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
        }
        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1px solid #eaeaea;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            margin-bottom: 15px;
        }
        .sidebar a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }
        .sidebar a.active {
            color: #d8456b;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .metric-card {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .metric-card h3 {
            margin: 0 0 8px;
            font-size: 1rem;
            color: #222;
        }
        .metric-card p {
            margin: 0;
            font-size: 1.4rem;
            font-weight: bold;
            color: #d8456b;
        }
        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        .car-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        .car-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .car-card .details {
            padding: 10px;
            flex-grow: 1;
        }
        .car-card .details h4 {
            margin: 0 0 5px;
            font-size: 1rem;
            color: #222;
        }
        .car-card .details p {
            margin: 0;
            font-size: 0.9rem;
            color: #555;
        }
        .car-card .details .price {
            margin-top: 8px;
            font-weight: bold;
            color: #28a745;
        }
        .car-card button {
            margin: 10px;
            padding: 8px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .car-card button:hover {
            background: #0056b3;
        }
        /* Mobile adjustments */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid #eaeaea; }
            .content { padding: 10px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">Car Shop</div>
        <div class="cart-bell">🔔<span id="cart-count">0</span></div>
    </header>
    <aside class="sidebar">
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="cars.php">Cars</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
    </aside>
    <main class="content">
        <section class="metrics">
            <div class="metric-card"><h3>Total Cars</h3><p><?php echo htmlspecialchars($totalCars); ?></p></div>
            <div class="metric-card"><h3>New Orders</h3><p><?php echo htmlspecialchars($newOrders); ?></p></div>
            <div class="metric-card"><h3>Users</h3><p><?php echo htmlspecialchars($totalUsers); ?></p></div>
            <div class="metric-card"><h3>Revenue</h3><p>$<?php echo number_format($revenue, 2); ?></p></div>
        </section>
        <section class="car-grid">
            <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <?php if (!empty($car['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['title']); ?>">
                    <?php else: ?>
                        <img src="placeholder.jpg" alt="Placeholder">
                    <?php endif; ?>
                    <div class="details">
                        <h4><?php echo htmlspecialchars($car['title']); ?></h4>
                        <p class="price">$<?php echo number_format($car['price'], 2); ?></p>
                    </div>
                    <button onclick="addToCart(<?php echo $car['id']; ?>)">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <script>
        // Simple cart count handler – replace with real logic if needed
        let cartCount = 0;
        function addToCart(id) {
            cartCount++;
            document.getElementById('cart-count').textContent = cartCount;
            // You can add an AJAX request here to persist the cart on the server
        }
    </script>
</body>
</html>

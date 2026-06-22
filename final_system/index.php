<?php
// index.php - Main Shop Page
session_start();

// Mock database array representing the cars. 
// In a fully robust system, these would also be fetched from the 'cars' DB table like the dashboard.
$cars = [
    [
        "image_file" => "nissan_juke.jpg",
        "make_model" => "NISSAN Juke 2017",
        "color" => "White",
        "mileage" => "117,207 km",
        "engine" => "1485cc",
        "transmission" => "Automatic",
        "fuel" => "Petrol",
        "price" => "1,400,000",
        "financing" => true,
        "locally_used" => true,
        "inspection" => false
    ],
    [
        "image_file" => "mazda_atenza.jpg",
        "make_model" => "MAZDA Atenza 2017",
        "color" => "Silver",
        "mileage" => "110,405 km",
        "engine" => "2180cc",
        "transmission" => "Automatic",
        "fuel" => "Diesel",
        "price" => "1,704,545",
        "financing" => true,
        "locally_used" => true,
        "inspection" => false
    ],
    [
        "image_file" => "mitsubishi_lancer.jpg",
        "make_model" => "MITSUBISHI Lancer 2004",
        "color" => "Black",
        "mileage" => "273,443 km",
        "engine" => "1468cc",
        "transmission" => "Automatic",
        "fuel" => "Petrol",
        "price" => "300,000",
        "financing" => false,
        "locally_used" => true,
        "inspection" => true
    ],
    [
        "image_file" => "suzuki_alto.jpg",
        "make_model" => "SUZUKI Alto 2018",
        "color" => "Blue",
        "mileage" => "374,845 km",
        "engine" => "800cc",
        "transmission" => "Manual",
        "fuel" => "Petrol",
        "price" => "336,000",
        "financing" => false,
        "locally_used" => true,
        "inspection" => false
    ],
    [
        "image_file" => "suzuki_swift.jpg",
        "make_model" => "SUZUKI Swift 2015",
        "color" => "Black",
        "mileage" => "124,364 km",
        "engine" => "1240cc",
        "transmission" => "Automatic",
        "fuel" => "Petrol",
        "price" => "985,600",
        "financing" => true,
        "locally_used" => true,
        "inspection" => false
    ],
    [
        "image_file" => "toyota_pixis.jpg",
        "make_model" => "TOYOTA Pixis 2013",
        "color" => "White",
        "mileage" => "150,815 km",
        "engine" => "650cc",
        "transmission" => "Automatic",
        "fuel" => "Petrol",
        "price" => "593,600",
        "financing" => true,
        "locally_used" => true,
        "inspection" => false
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rmt Cars – Shop</title>
    <link rel="stylesheet" href="style.css">
    <script src="cart.js" defer></script>
</head>
<body>
    <header>
        <div class="logo-container"><span></span> Rmt Cars</div>
        <nav>
            <ul>
                <li><a href="index.php">Shop</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="contact.php">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="cart-bell" id="cartBell">
            &#128722; <!-- shopping cart emoji -->
            <span class="count" id="cartCount">0</span>
        </div>
    </header>

    <main class="car-grid">
        <?php foreach ($cars as $car): ?>
            <div class="car-card">
                <div class="image-container">
                    <img src="images/<?php echo htmlspecialchars($car['image_file']); ?>" alt="<?php echo htmlspecialchars($car['make_model']); ?>">
                    <?php if ($car['financing']): ?>
                        <div class="financing-badge">Financing Available &#9432;</div>
                    <?php endif; ?>
                    <button class="heart-icon">&#9825;</button>
                </div>
                <div class="car-details">
                    <h3 class="car-title">
                        <?php echo htmlspecialchars($car['make_model']); ?> - <?php echo htmlspecialchars($car['color']); ?>
                    </h3>
                    <div class="car-specs">
                        <?php echo htmlspecialchars($car['mileage']); ?> · 
                        <?php echo htmlspecialchars($car['engine']); ?> · 
                        <?php echo htmlspecialchars($car['transmission']); ?> · 
                        <?php echo htmlspecialchars($car['fuel']); ?>
                    </div>
                    <div class="car-footer">
                        <div class="car-price">Ksh <?php echo htmlspecialchars($car['price']); ?></div>
                        <div class="tag-container">
                            <?php if ($car['inspection']): ?>
                                <a href="#" class="inspection-link">View Inspection Report &#8599;</a>
                            <?php endif; ?>
                            <?php if ($car['locally_used']): ?>
                                <div class="locally-used-badge">Locally Used</div>
                            <?php endif; ?>
                            <button class="btn add-to-cart" data-price="<?php echo htmlspecialchars($car['price']); ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>

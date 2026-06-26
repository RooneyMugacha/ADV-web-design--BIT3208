<?php
// db.php - Database connection settings
$host = 'localhost';
$dbname = 'cars'; // As requested
$username = 'root'; // Change this if your DB user is different
$password = ''; // Change this if your DB password is not empty

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Auto-setup DB tables if not exist
    // 1. Ensure users table exists with correct schema
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Add username column if missing
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'username'");
    $usernameExists = $stmt->fetch();
    if (!$usernameExists) {
        // Clear users table first so we don't have empty usernames violating constraints
        $pdo->exec("TRUNCATE TABLE users");
        $pdo->exec("ALTER TABLE users ADD COLUMN username VARCHAR(50) NOT NULL UNIQUE AFTER id");
    }

    // Add is_admin column if missing
    $stmtAdmin = $pdo->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    $isAdminExists = $stmtAdmin->fetch();
    if (!$isAdminExists) {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0 AFTER password");
    }

    // Create default admin user if users table is empty
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insertAdmin = $pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $insertAdmin->execute(['admin', 'admin@rmt.com', $adminPassword, 1]);
    }

    // 2. Ensure cars table exists with correct schema
    $pdo->exec("CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        make_model VARCHAR(100) NOT NULL,
        color VARCHAR(50) NOT NULL,
        mileage VARCHAR(50) NOT NULL,
        engine VARCHAR(50) NOT NULL,
        transmission VARCHAR(50) NOT NULL,
        fuel VARCHAR(50) NOT NULL,
        price DECIMAL(12, 2) NOT NULL,
        image_file VARCHAR(100) NOT NULL,
        financing TINYINT(1) DEFAULT 0,
        locally_used TINYINT(1) DEFAULT 1,
        inspection TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Populate cars table if empty
    $count = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
    if ($count == 0) {
        $carsData = [
            ["NISSAN Juke 2017", "White", "117,207 km", "1485cc", "Automatic", "Petrol", 1400000.00, "nissan_juke.jpg", 1, 1, 0],
            ["MAZDA Atenza 2017", "Silver", "110,405 km", "2180cc", "Automatic", "Diesel", 1704545.00, "mazda_atenza.jpg", 1, 1, 0],
            ["MITSUBISHI Lancer 2004", "Black", "273,443 km", "1468cc", "Automatic", "Petrol", 300000.00, "mitsubishi_lancer.jpg", 0, 1, 1],
            ["SUZUKI Alto 2018", "Blue", "374,845 km", "800cc", "Manual", "Petrol", 336000.00, "suzuki_alto.jpg", 0, 1, 0],
            ["SUZUKI Swift 2015", "Black", "124,364 km", "1240cc", "Automatic", "Petrol", 985600.00, "suzuki_swift.jpg", 1, 1, 0],
            ["TOYOTA Pixis 2013", "White", "150,815 km", "650cc", "Automatic", "Petrol", 593600.00, "toyota_pixis.jpg", 1, 1, 0]
        ];
        $insertStmt = $pdo->prepare("INSERT INTO cars (make_model, color, mileage, engine, transmission, fuel, price, image_file, financing, locally_used, inspection) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($carsData as $car) {
            $insertStmt->execute($car);
        }
    }

} catch (PDOException $e) {
    // If connection fails, stop script and show error
    die("Database connection failed: " . $e->getMessage());
}
?>
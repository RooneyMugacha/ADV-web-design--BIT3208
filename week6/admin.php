<?php
session_start();
require_once 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $make_model = $_POST['make_model'];
        $color = $_POST['color'];
        $mileage = $_POST['mileage'];
        $engine = $_POST['engine'];
        $transmission = $_POST['transmission'];
        $fuel = $_POST['fuel'];
        $price = $_POST['price'];
        $image_file = $_POST['image_file'];
        $financing = isset($_POST['financing']) ? 1 : 0;
        $locally_used = isset($_POST['locally_used']) ? 1 : 0;
        $inspection = isset($_POST['inspection']) ? 1 : 0;
        
        $stmt = $pdo->prepare("INSERT INTO cars (make_model, color, mileage, engine, transmission, fuel, price, image_file, financing, locally_used, inspection) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$make_model, $color, $mileage, $engine, $transmission, $fuel, $price, $image_file, $financing, $locally_used, $inspection]);
        header('Location: admin.php');
        exit();
    }
    
    // Handle Update
    if ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $make_model = $_POST['make_model'];
        $color = $_POST['color'];
        $mileage = $_POST['mileage'];
        $engine = $_POST['engine'];
        $transmission = $_POST['transmission'];
        $fuel = $_POST['fuel'];
        $price = $_POST['price'];
        $image_file = $_POST['image_file'];
        $financing = isset($_POST['financing']) ? 1 : 0;
        $locally_used = isset($_POST['locally_used']) ? 1 : 0;
        $inspection = isset($_POST['inspection']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE cars SET make_model = ?, color = ?, mileage = ?, engine = ?, transmission = ?, fuel = ?, price = ?, image_file = ?, financing = ?, locally_used = ?, inspection = ? WHERE id = ?");
        $stmt->execute([$make_model, $color, $mileage, $engine, $transmission, $fuel, $price, $image_file, $financing, $locally_used, $inspection, $id]);
        header('Location: admin.php');
        exit();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin.php');
    exit();
}

// Fetch all cars
$cars = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cars</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="main.css">
</head>
<body class="admin-page">
    <header>
        <div class="logo-container">Car Shop Admin</div>
        <nav>
            <ul>
                <li><a href="index.php">Shop</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="admin.php" style="color: #d8456b;">Manage Cars</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container" style="max-width: 1200px; margin-top: 30px;">
        <h1>Car Management System</h1>
        
        <!-- Create Car Form -->
        <div class="form-container">
            <h2>Add New Car</h2>
            <form id="createForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <input type="text" name="make_model" placeholder="Make & Model" required>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" name="price" placeholder="Price (Ksh)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="color" placeholder="Color" required>
                </div>
                <div class="form-group">
                    <input type="text" name="mileage" placeholder="Mileage (e.g. 100,000 km)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="engine" placeholder="Engine (e.g. 1500cc)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="transmission" placeholder="Transmission (Automatic/Manual)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fuel" placeholder="Fuel Type (Petrol/Diesel)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="image_file" placeholder="Image Filename (e.g. car.jpg)" required>
                </div>
                <div class="form-group" style="grid-column: span 2; display: flex; gap: 20px;">
                    <label><input type="checkbox" name="financing" value="1"> Financing Available</label>
                    <label><input type="checkbox" name="locally_used" value="1" checked> Locally Used</label>
                    <label><input type="checkbox" name="inspection" value="1"> Inspection Report</label>
                </div>
                <button type="submit" class="btn btn-primary" style="grid-column: span 2;">Add Car</button>
            </form>
        </div>

        <!-- Cars Table -->
        <div class="table-container">
            <h2>Car Inventory</h2>
            <table id="carsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Make & Model</th>
                        <th>Price</th>
                        <th>Color</th>
                        <th>Mileage</th>
                        <th>Specs</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                    <tr data-id="<?= $car['id'] ?>">
                        <td><?= htmlspecialchars($car['id']) ?></td>
                        <td>
                            <?php if (!empty($car['image_file'])): ?>
                                <img src="images/<?= htmlspecialchars($car['image_file']) ?>" alt="car" width="60" style="border-radius: 4px;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="car-make_model"><?= htmlspecialchars($car['make_model']) ?></td>
                        <td class="car-price"><?= htmlspecialchars($car['price']) ?></td>
                        <td class="car-color"><?= htmlspecialchars($car['color']) ?></td>
                        <td class="car-mileage"><?= htmlspecialchars($car['mileage']) ?></td>
                        <td>
                            <span class="car-engine" style="display:none;"><?= htmlspecialchars($car['engine']) ?></span>
                            <span class="car-transmission" style="display:none;"><?= htmlspecialchars($car['transmission']) ?></span>
                            <span class="car-fuel" style="display:none;"><?= htmlspecialchars($car['fuel']) ?></span>
                            <span class="car-image_file" style="display:none;"><?= htmlspecialchars($car['image_file']) ?></span>
                            <span class="car-financing" style="display:none;"><?= htmlspecialchars($car['financing']) ?></span>
                            <span class="car-locally_used" style="display:none;"><?= htmlspecialchars($car['locally_used']) ?></span>
                            <span class="car-inspection" style="display:none;"><?= htmlspecialchars($car['inspection']) ?></span>
                            <small><?= htmlspecialchars($car['engine']) ?> | <?= htmlspecialchars($car['transmission']) ?> | <?= htmlspecialchars($car['fuel']) ?></small>
                        </td>
                        <td class="actions">
                            <button class="btn btn-edit" onclick="editCar(<?= $car['id'] ?>)">Edit</button>
                            <button class="btn btn-delete" onclick="deleteCar(<?= $car['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Car</h2>
            <form id="editForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <input type="text" name="make_model" id="editMakeModel" placeholder="Make & Model" required>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" name="price" id="editPrice" placeholder="Price (Ksh)" required>
                </div>
                <div class="form-group">
                    <input type="text" name="color" id="editColor" placeholder="Color" required>
                </div>
                <div class="form-group">
                    <input type="text" name="mileage" id="editMileage" placeholder="Mileage" required>
                </div>
                <div class="form-group">
                    <input type="text" name="engine" id="editEngine" placeholder="Engine" required>
                </div>
                <div class="form-group">
                    <input type="text" name="transmission" id="editTransmission" placeholder="Transmission" required>
                </div>
                <div class="form-group">
                    <input type="text" name="fuel" id="editFuel" placeholder="Fuel Type" required>
                </div>
                <div class="form-group">
                    <input type="text" name="image_file" id="editImageFile" placeholder="Image Filename" required>
                </div>
                <div class="form-group" style="grid-column: span 2; display: flex; gap: 20px;">
                    <label><input type="checkbox" name="financing" id="editFinancing" value="1"> Financing Available</label>
                    <label><input type="checkbox" name="locally_used" id="editLocallyUsed" value="1"> Locally Used</label>
                    <label><input type="checkbox" name="inspection" id="editInspection" value="1"> Inspection Report</label>
                </div>
                <button type="submit" class="btn btn-primary" style="grid-column: span 2;">Update Car</button>
            </form>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>
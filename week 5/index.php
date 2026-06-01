<?php
require_once 'db.php';

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $stock]);
        header('Location: index.php');
        exit();
    }
    
    // Handle Update
    if ($_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $id]);
        header('Location: index.php');
        exit();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: index.php');
    exit();
}

// Fetch all products
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Product Management System</h1>
        
        <!-- Create Product Form -->
        <div class="form-container">
            <h2>Add New Product</h2>
            <form id="createForm">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" name="price" placeholder="Price" required>
                </div>
                <div class="form-group">
                    <input type="number" name="stock" placeholder="Stock Quantity" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>

        <!-- Products Table -->
        <div class="table-container">
            <h2>Product List</h2>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr data-id="<?= $product['id'] ?>">
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                        <td class="product-price">ksh<?= number_format($product['price'], 2) ?></td>
                        <td class="product-stock"><?= htmlspecialchars($product['stock']) ?></td>
                        <td><?= htmlspecialchars($product['created_at']) ?></td>
                        <td class="actions">
                            <button class="btn btn-edit" onclick="editProduct(<?= $product['id'] ?>)">Edit</button>
                            <button class="btn btn-delete" onclick="deleteProduct(<?= $product['id'] ?>)">Delete</button>
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
            <h2>Edit Product</h2>
            <form id="editForm">
                <input type="hidden" name="id" id="editId">
                <div class="form-group">
                    <input type="text" name="name" id="editName" placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" name="price" id="editPrice" placeholder="Price" required>
                </div>
                <div class="form-group">
                    <input type="number" name="stock" id="editStock" placeholder="Stock Quantity" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$user = $_SESSION['user'];
$isAdmin = $user['role'] === 'admin';

if (!$isAdmin) {
    echo "Access denied. You are not an admin.";
    exit();
}

// Fetch recent sales
$stmt = $pdo->query("SELECT s.id, u.username AS cashier, s.total 
                     FROM sales s
                     JOIN users u ON s.user_id = u.id
                     ORDER BY s.id DESC LIMIT 10");
$sales = $stmt->fetchAll();

// Fetch products
$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

// Add Staff Logic (with full_name)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $fullName = $firstName . ' ' . $lastName;

    if (empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
        $_SESSION['staff_message'] = "All fields are required.";
        $_SESSION['staff_status'] = "error";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $checkStmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $checkStmt->execute([$username]);

        if ($checkStmt->rowCount() > 0) {
            $_SESSION['staff_message'] = "Username already exists. Please choose another.";
            $_SESSION['staff_status'] = "error";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, fullname) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role, $fullName]);
            $_SESSION['staff_message'] = "Staff added successfully!";
            $_SESSION['staff_status'] = "success";
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Add Product Logic (extended for ecommerce)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $supplier = $_POST['supplier'];
    $category = $_POST['category'] ?? 'General';
    $image = 'default.png'; // Placeholder, will be updated by upload logic

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if ($_FILES['image']['size'] > 2000000) { // 2MB limit
            echo "<script>alert('Image size exceeds 2MB.');</script>";
            exit;
        }
        if (!getimagesize($imageTmpPath)) {
            echo "<script>alert('File is not a valid image.');</script>";
            exit;
        }
        if (in_array($imageExt, $allowedExts)) {
            $newImageName = uniqid('img_') . '.' . $imageExt;
            $uploadDir = 'product/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $destination = $uploadDir . $newImageName;

            if (move_uploaded_file($imageTmpPath, $destination)) {
                $image = $newImageName;
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('Invalid image format. Only JPG, PNG, and GIF allowed.');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Image is required.');</script>";
        exit;
    }

    if (empty($barcode) || empty($name) || empty($price) || empty($stock) || empty($supplier)) {
        echo "<script>alert('All fields must be filled in.');</script>";
    } else {
        $checkStmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
        $checkStmt->execute([$barcode]);
        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('Product with this barcode already exists.');</script>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (barcode, name, price, stock, supplier, category, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$barcode, $name, $price, $stock, $supplier, $category, $image]);
            echo "<script>alert('Product added successfully!');</script>";
        }
    }
}

// Delete Product Logic
if (isset($_GET['delete_product_id'])) {
    $productId = $_GET['delete_product_id'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    $_SESSION['product_message'] = "Product deleted successfully!";
    $_SESSION['product_status'] = "success";

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Update Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $productId = $_POST['product_id'];
    $newPrice = $_POST['new_price'];
    $newStock = $_POST['new_stock'];

    if (!is_numeric($newPrice) || !is_numeric($newStock)) {
        echo "<script>alert('Price and stock must be numeric.');</script>";
    } else {
        $stmt = $pdo->prepare("UPDATE products SET price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$newPrice, $newStock, $productId]);

        $_SESSION['product_message'] = "Product updated successfully!";
        $_SESSION['product_status'] = "success";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard - Furrfect Pawshop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        /* Style for Update button */
        button[type="submit"][name="update_product"] {
            background-color: #f4a261;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="submit"][name="update_product"]:hover {
            background-color: #e76f51;
        }

        /* Style for Delete button */
        button[type="button"] {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button[type="button"]:hover {
            background-color: #c0392b;
        }

        /* Body and Layout */
        body {
            font-family: 'Fredoka', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/paw-print.png') repeat, #fff8f0;
            background-size: 60px 60px;
            margin: 0;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #cc8b00; /* Changed from #2c3e50 to yellow ochre */
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 20px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            font-size: 16px;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #b07b00; /* Changed from #34495e to darker yellow ochre */
            border-radius: 4px;
        }
        .sidebar ul li a i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }
        header {
            background-color: #cc8b00; /* Changed from #2c3e50 to yellow ochre */
            color: white;
            padding: 20px 0;
        }
        .navbar {
            width: 90%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 { font-size: 28px; }
        .user-info { display: flex; align-items: center; }
        .user-info span { margin-right: 10px; }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .logout-btn:hover { background-color: #c0392b; }
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px auto;
            width: 90%;
        }
        .dashboard-card {
            background-color: #fffbea;
            border: 2px solid #f7d9a4;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            flex: 1 1 48%;
            padding: 20px;
        }
        h2 { color: #86592d; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th { background-color: #cc8b00; /* Changed from #34495e to yellow ochre */
            color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        form input, form select, form button {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: #f4a261;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover { background-color: #e76f51; }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3><a href="dashboard.php" style="color: white; text-decoration: none;">Admin Dashboard</a></h3>
        <ul>
            <li><a href="#recent-sales"><i class="fas fa-chart-line"></i> Recent Sales</a></li>
            <li><a href="#product-management"><i class="fas fa-box"></i> Product Management</a></li>
            <li><a href="#add-staff"><i class="fas fa-user-plus"></i> Add Staff</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <div class="navbar">
                <h1>Furrfect Pawshop</h1>
                <div class="user-info">
                    <span>Logged in as: <?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)</span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </header>

        <main>
            <?php if (isset($_SESSION['product_message'])): ?>
            <div style="width: 90%; margin: 20px auto; padding: 10px; background-color: <?= $_SESSION['product_status'] === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $_SESSION['product_status'] === 'success' ? '#155724' : '#721c24' ?>; border-radius: 5px; text-align: center;">
                <?= htmlspecialchars($_SESSION['product_message']) ?>
            </div>
            <?php unset($_SESSION['product_message'], $_SESSION['product_status']); ?>
            <?php endif; ?>

            <section class="dashboard-container">
                <!-- Recent Sales -->
                <div class="dashboard-card" id="recent-sales">
                    <h2>Recent Sales</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Sale ID</th>
                                <th>Cashier</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td><?= htmlspecialchars($sale['id']) ?></td>
                                <td><?= htmlspecialchars($sale['cashier']) ?></td>
                                <td>₱<?= number_format($sale['total'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Product Management -->
                <div class="dashboard-card" id="product-management">
                    <h2>Product Management</h2>
                    <form id="add-product-form" action="" method="POST" enctype="multipart/form-data">
                        <input type="text" name="barcode" placeholder="Barcode" required>
                        <input type="text" name="name" placeholder="Product Name" required>
                        <input type="number" step="0.01" name="price" placeholder="Price" required>
                        <input type="number" name="stock" placeholder="Stock" required>
                        <input type="text" name="supplier" placeholder="Supplier Name" required>
                        <select name="category" required>
                            <option value="">-- Select Category --</option>
                            <option value="Drooly Delights">Drooly Delights</option>
                            <option value="Feline Feast">Feline Feast</option>
                            <option value="Fur & Shine">Fur & Shine</option>
                            <option value="Litter Lodge">Litter Lodge</option>
                            <option value="Snug Paws Haven">Snug Paws Haven</option>
                            <option value="Tails & Trails">Tails & Trails</option>
                            <option value="Vital Paws">Vital Paws</option>
                        </select>
                        <input type="file" name="image" accept="image/*" required>
                        <button type="submit" name="add_product" value="1">Add Product</button>
                    </form>

                    <h3>Existing Products</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Barcode</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="product-table-body">
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <form action="" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <td>
                                        <img src="<?= file_exists('product/' . $product['image']) ? 'product/' . htmlspecialchars($product['image']) : 'product/default.png' ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             style="width: 50px; height: 50px; object-fit: contain;">
                                    </td>
                                    <td><?= htmlspecialchars($product['barcode']) ?></td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td>
                                        <input type="number" step="0.01" name="new_price" value="<?= $product['price'] ?>" style="width: 80px;">
                                    </td>
                                    <td>
                                        <input type="number" name="new_stock" value="<?= $product['stock'] ?>" style="width: 60px;">
                                    </td>
                                    <td><?= htmlspecialchars($product['supplier']) ?></td>
                                    <td><?= htmlspecialchars($product['category'] ?: 'General') ?></td>
                                    <td>
                                        <button type="submit" name="update_product">Update</button>
                                        <a href="?delete_product_id=<?= $product['id'] ?>"><button type="button">Delete</button></a>
                                    </td>
                                </form>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Staff -->
                <div class="dashboard-card" id="add-staff">
                    <h2>Add Staff</h2>
                    <?php if (isset($_SESSION['staff_message'])): ?>
                        <div style="padding: 10px; margin-bottom: 10px; background-color: <?= $_SESSION['staff_status'] === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $_SESSION['staff_status'] === 'success' ? '#155724' : '#721c24' ?>; border-radius: 5px;">
                            <?= htmlspecialchars($_SESSION['staff_message']) ?>
                        </div>
                        <?php unset($_SESSION['staff_message'], $_SESSION['staff_status']); ?>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <input type="text" name="first_name" placeholder="First Name" required>
                        <input type="text" name="last_name" placeholder="Last Name" required>
                        <input type="text" name="username" placeholder="Staff Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <select name="role" required>
                            <option value="cashier">Cashier</option>
                        </select>
                        <button type="submit" name="add_staff">Register Staff</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('add-product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('add_product', '1');

            fetch('add_product_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! Status: ${res.status}`);
                }
                return res.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert("Product added successfully!");
                        fetch('fetch_products_html.php')
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to fetch product table');
                                }
                                return response.text();
                            })
                            .then(html => {
                                document.getElementById("product-table-body").innerHTML = html;
                                document.getElementById('add-product-form').reset();
                            })
                            .catch(error => {
                                console.error('Error fetching product table:', error);
                                alert('Error refreshing product table: ' + error.message);
                            });
                    } else {
                        alert(data.message || "Error adding product.");
                    }
                } catch (e) {
                    console.error('JSON parse error:', e, 'Response:', text);
                    alert('Error parsing server response: ' + e.message);
                }
            })
            .catch(error => {
                console.error('Error adding product:', error);
                alert('Error adding product: ' + error.message);
            });
        });
    </script>
</body>
</html>
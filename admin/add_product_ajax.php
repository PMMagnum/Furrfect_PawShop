<?php
// Clear output buffer to prevent stray output
ob_clean();
// Set JSON header
header('Content-Type: application/json; charset=utf-8');
// Disable error display to prevent corrupting JSON
ini_set('display_errors', 0);
// Log errors to a file for debugging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

session_start();
include 'db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $barcode = $_POST['barcode'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $supplier = $_POST['supplier'] ?? '';
    $category = $_POST['category'] ?? 'General';
    $image = 'default.png'; // Placeholder, will be updated by upload logic

    // Validate required fields
    if (empty($barcode) || empty($name) || empty($price) || empty($stock) || empty($supplier)) {
        echo json_encode(['success' => false, 'message' => 'All fields must be filled in.']);
        exit;
    }

    // Image upload logic
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageExt, $allowedExts)) {
            echo json_encode(['success' => false, 'message' => 'Invalid image format. Only JPG, PNG, and GIF allowed.']);
            exit;
        }

        $newImageName = uniqid('img_') . '.' . $imageExt;
        $uploadDir = 'Uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
                exit;
            }
        }
        $destination = $uploadDir . $newImageName;

        if (!move_uploaded_file($imageTmpPath, $destination)) {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            exit;
        }
        $image = $newImageName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Image is required.']);
        exit;
    }

    // Check for duplicate barcode
    $checkStmt = $pdo->prepare("SELECT * FROM products WHERE barcode = ?");
    $checkStmt->execute([$barcode]);
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Product with this barcode already exists.']);
        exit;
    }

    // Insert product
    $stmt = $pdo->prepare("INSERT INTO products (barcode, name, price, stock, supplier, category, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$barcode, $name, $price, $stock, $supplier, $category, $image]);

    // Return success with image path
    $fullImagePath = $uploadDir . $image; // Relative path
    echo json_encode([
        'success' => true,
        'message' => 'Product added successfully.',
        'image_path' => $fullImagePath
    ]);
    exit;

} catch (Exception $e) {
    // Log the error
    error_log('Error in add_product_ajax.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>

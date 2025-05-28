<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barcode = $_POST['barcode'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    $stmt = $pdo->prepare("INSERT INTO products (barcode, name, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$barcode, $name, $price, $stock, $image]);

    header('Location: dashboard.php');
    exit();
}
?>

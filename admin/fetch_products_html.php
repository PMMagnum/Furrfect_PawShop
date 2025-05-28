<?php
include 'db.php';

$productStmt = $pdo->query("SELECT * FROM products");
$products = $productStmt->fetchAll();

foreach ($products as $product): ?>
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
        <td><input type="number" step="0.01" name="new_price" value="<?= $product['price'] ?>" style="width: 80px;"></td>
        <td><input type="number" name="new_stock" value="<?= $product['stock'] ?>" style="width: 60px;"></td>
        <td><?= htmlspecialchars($product['supplier']) ?></td>
        <td><?= htmlspecialchars($product['category'] ?: 'General') ?></td>
        <td>
            <button type="submit" name="update_product">Update</button>
            <a href="?delete_product_id=<?= $product['id'] ?>"><button type="button">Delete</button></a>
        </td>
    </form>
</tr>
<?php endforeach; ?>
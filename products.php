<?php
session_start();
require 'config.php';
require 'header.php'; // optional: extract header into reusable file
?>
<style>
  body{
    margin: 0;
    display: flex;
    flex-direction: column;
  }
</style>
<main class="container mt-5">
  <h2 class="mb-4">Our Products</h2>
  <div class="row row-cols-1 row-cols-md-3 g-4">
     <link rel="stylesheet" href="products.css" />
    <?php
    $result = $conn->query("SELECT * FROM products");
    while ($product = $result->fetch_assoc()):
    ?>
      <div class="col product" data-category="<?= htmlspecialchars($product['category'] ?? '') ?>">
        <div class="card h-100">
          <img src="<?= file_exists('product/' . $product['image']) ? 'product/' . htmlspecialchars($product['image']) : 'product/default.png' ?>" 
               class="card-img-top p-3" 
               alt="<?= htmlspecialchars($product['name']) ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="card-text">₱<?= number_format($product['price'], 2) ?></p>
            <form method="post" action="main.php">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
              <button type="submit" class="btn btn-primary mt-auto">Add to Cart</button>
            </form>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<?php include 'footer.php'; ?>
<?php
session_start();
require 'config.php';
require 'header.php'; // Your header.php should include the Cart button with id="cartBtn" and badge

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    if ($product) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!-- =========================
       Cart Modal
========================= -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="cartContents">
        <?php if (empty($_SESSION['cart'])): ?>
          <p>Your cart is empty.</p>
        <?php else: ?>
          <table class="table">
            <thead>
              <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th>Remove</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              foreach ($_SESSION['cart'] as $id => $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
              ?>
              <tr>
                <td>
                    <img src="<?= file_exists('products/' . $item['image']) ? 'products/' . htmlspecialchars($item['image']) : 'products/default.png' ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>" 
                         style="width: 50px; height: 50px; object-fit: contain;">
                </td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td>₱<?= number_format($subtotal, 2) ?></td>
                <td>
                  <form method="post" action="remove_from_cart.php" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            <tfoot>
              <tr>
                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                <td colspan="2">₱<?= number_format($total, 2) ?></td>
              </tr>
              <tr>
                <td colspan="6" class="text-center">
                  <a href="checkout.php" class="btn btn-primary">Check Out</a>
                </td>
              </tr>
            </tfoot>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- =========================
       Main Content Starts
========================= -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Furrfect Pawshop</title>
<!-- Your styles and fonts -->
<link rel="stylesheet" href="style.css" />
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<style>
.category-link {
    background-color: #D8AE7E;
    height: 100px;
    width: 115px;               /* adjusted width to include padding */
    font-weight: bold;
    color: white;
    border-radius: 5px;
    padding: 10px 60px 10px 10px; /* shorthand for padding-top/right/bottom/left */
    margin-bottom: 30px;
    box-sizing: border-box;
    /* remove position: absolute; unless you have a specific reason */
}
.list-unstyled {
  gap: 25px;
  display: flex;
  flex-direction: column;
}
.product .buttons form:nth-child(2) button {
    display: block !important;
    visibility: visible !important;
}
/* Added alert styles from contact.php */
.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 12px;
    text-align: center;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
</style>
<body>

<!-- =========================
       Banner Section
========================= -->
<div class="container-fluid px-0 mb-4">
  <div class="row g-0">
    <div class="col-12">
      <div id="mainBannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="images/him.jpg" class="d-block w-100 img-fluid rounded" alt="Banner 1" style="object-fit:cover; max-height:400px;">
          </div>
          <div class="carousel-item">
            <img src="images/landing.jpg" class="d-block w-100 img-fluid rounded" alt="Banner 2" style="object-fit:cover; max-height:400px;">
          </div>
          <div class="carousel-item">
            <img src="images/doggocatto.jpg" class="d-block w-100 img-fluid rounded" alt="Banner 3" style="object-fit:cover; max-height:400px;">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainBannerCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>
</div>

<main class="container-fluid px-2 px-md-4 mt-4">
  <!-- Added success message display -->
  <?php if (isset($_SESSION['contact_success'])): ?>
    <div class="alert alert-success">
        <?php 
        echo htmlspecialchars($_SESSION['contact_success']); 
        unset($_SESSION['contact_success']); // Clear the session variable
        ?>
    </div>
  <?php endif; ?>
  <div class="row">
    <!-- Sidebar categories -->
    <aside class="col-12 col-md-3 mb-4">
      <div class="card h-100 w-75 shadow-sm">
        <div class="card-body">
          <h3 class="h5">Categories</h3>
          <ul class="list-unstyled">
            <li><a href="#" class="category-link" data-category="all" style="text-decoration: none;">All Products</a></li>
            <li><a href="#" class="category-link" data-category="Drooly Delights" style="text-decoration: none;">Drooly Delights</a></li>
            <li><a href="#" class="category-link" data-category="Feline Feast" style="text-decoration: none;">Feline Feast</a></li>
            <li><a href="#" class="category-link" data-category="Fur & Shine" style="text-decoration: none;">Fur & Shine</a></li>
            <li><a href="#" class="category-link" data-category="Litter Lodge" style="text-decoration: none;">Litter Lodge</a></li>
            <li><a href="#" class="category-link" data-category="Snug Paws Haven" style="text-decoration: none;">Snug Paws Haven</a></li>
            <li><a href="#" class="category-link" data-category="Tails & Trails" style="text-decoration: none;">Tails & Trails</a></li>
            <li><a href="#" class="category-link" data-category="Vital Paws" style="text-decoration: none;">Vital Paws</a></li>
          </ul>
        </div>
      </div>
    </aside>

    <!-- Main product list -->
    <section class="col-12 col-md-9">
      <section class="mb-4">
        <input type="text" placeholder="Search for products..." id="search-input" class="form-control form-control-lg w-100" />
      </section>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 product-list">
  <?php
  $result = $conn->query("SELECT * FROM products");
  while ($product = $result->fetch_assoc()):
    $imagePath = 'products/' . $product['image'];
    $imageSrc = file_exists(__DIR__ . '/' . $imagePath) ? $imagePath : 'products/default.png';
  ?>
    <div class="col d-flex">
      <div class="card h-100 w-100 shadow-sm product" data-category="<?= htmlspecialchars($product['category'] ?? '') ?>">
        <img src="<?= file_exists('product/' . $product['image']) ? 'product/' . htmlspecialchars($product['image']) : 'product/default.png' ?>"
             class="card-img-top p-3 img-fluid"
             alt="<?= htmlspecialchars($product['name']) ?>"
             style="object-fit:contain; max-height:180px;">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title text-beige fw-semibold"><?= htmlspecialchars($product['name']) ?></h5>
          <p class="card-text text-muted fw-semibold">₱<?= number_format($product['price'], 2) ?></p>
          <div class="buttons">
            <form method="post">
              <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <button type="submit" class="btn btn-beige w-100">Add to Cart</button>
            </form>
            <form action="checkout.php" method="get">
              <input type="hidden" name="buy_product_id" value="<?= $product['id'] ?>">
              <button type="submit" class="btn btn-beige w-100">Buy Now</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>
<div class="footer-newsletter py-4" style="background:#FAF1E6;">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
    <div class="mb-3 mb-md-0">
      <h5 class="mb-1" style="font-weight:700; color:#5F8B4C;">Get the best deals!</h5>
      <div style="color:#6F826A;">Hear first about our exclusive offers and pet care advice.</div>
    </div>
    <form class="d-flex" style="max-width:400px; width:100%;">
      <input type="email" class="form-control me-2" placeholder="Enter your email address" required>
      <button class="btn btn-beige" type="submit" style="background:#c99a60; color:white; font-weight:600;">Sign me up!</button>
    </form>
    <div class="ms-md-4 mt-3 mt-md-0 text-center text-md-end">
      <div style="font-weight:600; color:#5F8B4C;">Let's be friends</div>
      <div class="footer-social mt-2">
        <a href="#" class="me-2"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" class="me-2"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" class="me-2"><i class="fa-brands fa-twitter"></i></a>
        <a href="#" class="me-2"><i class="fa-brands fa-youtube"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- =========================
       Scripts
========================= -->
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Category filtering
  document.querySelectorAll('.category-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const cat = e.target.dataset.category;
      document.querySelectorAll('.product').forEach(prod => {
        prod.style.display = (cat === 'all' || prod.dataset.category === cat) ? 'block' : 'none';
      });
    });
  });

  // Search filter
  document.getElementById('search-input').addEventListener('input', e => {
    const val = e.target.value.toLowerCase();
    document.querySelectorAll('.product').forEach(prod => {
      const text = prod.textContent.toLowerCase();
      prod.style.display = text.includes(val) ? 'block' : 'none';
    });
  });

  // Top Deal item clicks
  document.querySelectorAll('.top-deal-item').forEach(item => {
    item.addEventListener('click', () => {
      const cat = item.dataset.category;
      document.querySelectorAll('.product').forEach(prod => {
        prod.style.display = (prod.dataset.category === cat) ? 'block' : 'none';
      });
      document.querySelector('.product-list').scrollIntoView({ behavior: 'smooth' });
    });
    item.style.cursor = 'pointer';
  });

  // Open cart modal on button click
  document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cartBtn');
    if (cartBtn) {
      cartBtn.addEventListener('click', () => {
        const myModal = new bootstrap.Modal(document.getElementById('cartModal'));
        myModal.show();
      });
    }
  });
</script>

<!-- Optional: your custom hover effects -->
<style>
.top-deal-item {
  transition: transform 0.2s, box-shadow 0.2s;
}
.top-deal-item:hover {
  transform: translateY(-8px) scale(1.05);
  box-shadow: 0 8px 24px rgba(124,94,182,0.18);
  background: #f8f4ff;
}
</style>
<?php require 'footer.php'; ?>
</body>
</html>
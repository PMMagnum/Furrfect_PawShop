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

<!-- Cart Modal -->
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

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="Furrfect_PawShop/images/finallogo.png">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Furrfect Pawshop</title>
  <link rel="stylesheet" href=".css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      overflow-x: hidden;
      font-family: 'Poppins', sans-serif;
    }

    .container-fluid {
      width: 100vw;
      padding: 0;
      margin: 0;
    }

    .row {
      margin: 0;
    }

    /* Banner Section */
    .banner {
      position: relative;
      width: 100%;
      height: 50vh;
      background: url('images/landing.jpg') no-repeat center center/cover;
    }
    .banner-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
    }
    .banner-overlay h1 {
      font-size: 2.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }
    .banner-overlay p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }
    .banner-overlay .btn {
      background-color: #D8AE7E;
      color: white;
      border: none;
      padding: 10px 30px;
      font-size: 1.1rem;
      font-weight: 500;
      border-radius: 5px;
    }
    .banner-overlay .btn:hover {
      background-color: #A67B5B;
    }

    /* Search Bar */
    .search-section {
      padding: 20px 0;
      background-color: #f8f9fa;
    }

    /* Categories Sidebar */
    .categories {
      padding: 20px;
      background-color: #fff;
      border-right: 1px solid #ddd;
      height: 100%;
      position: sticky;
      top: 20px;
    }
    .categories h3 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #D8AE7E;
      margin-bottom: 20px;
    }
    .categories a {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      color: #D8AE7E; /* Changed from #5A6A7C to beige */
      text-decoration: none;
      font-weight: 500;
      font-size: 1.1rem;
      text-transform: uppercase;
    }
    .categories a i {
      margin-right: 10px;
      font-size: 1.2rem;
    }
    .categories a:hover {
      color: #D8AE7E;
      background-color: #f9f1e7;
      border-radius: 5px;
    }

    /* Products Section */
    .product-list {
      margin-top: 20px;
    }
    .product .card {
      border: 1px solid #ddd;
      border-radius: 10px;
      overflow: hidden;
      text-align: center;
    }
    .product .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }
    .product .card-body {
      padding: 15px;
    }
    .product .card-title {
      font-size: 1.1rem;
      font-weight: 500;
      color: #333;
      margin-bottom: 10px;
    }
    .product .card-text {
      font-size: 1.2rem;
      font-weight: 600;
      color: #D8AE7E;
      margin-bottom: 15px;
    }
    .product .btn {
      background-color: #5A6A7C;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      font-weight: 500;
      text-transform: uppercase;
    }
    .product .btn:hover {
      background-color: #D8AE7E;
    }

    /* Featured Products Heading */
    .featured-products h2 {
      color: #D8AE7E;
      font-weight: 600;
    }

    /* Alert Styles */
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

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .categories {
        position: relative;
        top: 0;
        border-right: none;
        border-bottom: 1px solid #ddd;
      }
      .row {
        flex-direction: column;
      }
      .col-md-3,
      .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Banner Section -->
<div id="bannerCarousel" class="carousel slide banner" data-bs-ride="carousel" style="overflow:hidden;">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images\hachi.jpg" class="d-block w-100" alt="Furrfect Pawshop Banner" style="height:50vh;object-fit:cover;">
    </div>
    <div class="carousel-item">
      <img src="images\mainl.jpg" class="d-block w-100" alt="Furrfect Pawshop Banner 2" style="height:50vh;object-fit:cover;">
    </div>
    
    
    <!-- Add more images here if needed -->
  </div>
  <div class="banner-overlay" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;">
    <h1>Welcome to Furrfect Pawshop</h1>
    <p>Your one-stop shop for all your pet's needs. From premium food to cozy beds, we have everything to keep your furry friends happy!</p>
    <a href="#products" class="btn">Shop Now</a>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Search Bar -->
<div class="search-section">
  <div class="container">
    <input type="text" placeholder="Search for pet products..." id="search-input" class="form-control form-control-lg w-100" />
  </div>
</div>

<main class="container-fluid px-2 px-md-4 mt-4">
  <!-- Success Message Display -->
  <?php if (isset($_SESSION['contact_success'])): ?>
    <div class="alert alert-success">
      <?php echo htmlspecialchars($_SESSION['contact_success']); unset($_SESSION['contact_success']); ?>
    </div>
  <?php endif; ?>

  <div class="row">
   <!-- Sidebar categories -->
    <aside class="col-12 col-md-3 mb-4">
      <div class="card h-100 w-75 shadow-sm">
        <div class="card-body">
          <h3 class="h5">Categories</h3>
<ul class="list-unstyled">
  <li><a href="#" class="category-link" data-category="all" style="text-decoration: none; color: #A67B5B;">All Products</a></li>
  <li><a href="#" class="category-link" data-category="Drooly Delights" style="text-decoration: none; color: #A67B5B;">Drooly Delights</a></li>
  <li><a href="#" class="category-link" data-category="Feline Feast" style="text-decoration: none; color: #A67B5B;">Feline Feast</a></li>
  <li><a href="#" class="category-link" data-category="Fur & Shine" style="text-decoration: none; color: #A67B5B;">Fur & Shine</a></li>
  <li><a href="#" class="category-link" data-category="Litter Lodge" style="text-decoration: none; color: #A67B5B;">Litter Lodge</a></li>
  <li><a href="#" class="category-link" data-category="Snug Paws Haven" style="text-decoration: none; color: #A67B5B;">Snug Paws Haven</a></li>
  <li><a href="#" class="category-link" data-category="Tails & Trails" style="text-decoration: none; color: #A67B5B;">Tails & Trails</a></li>
  <li><a href="#" class="category-link" data-category="Vital Paws" style="text-decoration: none; color: #A67B5B;">Vital Paws</a></li>
</ul>

      </div>
    </aside>

    <!-- Main Product Section -->
    <section class="col-12 col-md-9 featured-products" id="products">
      <h2 class="h4 mb-4">Featured Products</h2>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 product-list">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($product = $result->fetch_assoc()):
          $imagePath = 'products/' . $product['image'];
          $imageSrc = file_exists(_DIR_ . '/' . $imagePath) ? $imagePath : 'products/default.png';
        ?>
        <div class="col d-flex">
          <div class="card h-100 w-100 product" data-category="<?= htmlspecialchars($product['category'] ?? '') ?>">
            <img src="<?= file_exists('products/' . $product['image']) ? 'products/' . htmlspecialchars($product['image']) : 'products/default.png' ?>"
                 class="card-img-top"
                 alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text">₱<?= number_format($product['price'], 2) ?></p>
              <div class="buttons">
                <form method="post">
                  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                  <button type="submit" class="btn">Add to Cart</button>
                </form>
                <form action="checkout.php" method="get">
                  <input type="hidden" name="buy_product_id" value="<?= $product['id'] ?>">
                  <button type="submit" class="btn btn-beige w-100 d-none">Buy Now</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </section>
  </div>
</main>

<?php require 'footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Category filtering
  document.querySelectorAll('.categories a').forEach(link => {
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
</body>
</html>
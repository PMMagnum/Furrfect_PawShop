<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "furfect_db");
if ($mysqli->connect_error) {
    die("DB Connection failed: " . $mysqli->connect_error);
}

// Handle "Buy Now" form submission
if (isset($_POST['buy_now']) && isset($_POST['product_id'])) {
    // Add the product to the cart
    $productId = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Initialize or update quantity for the product
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += 1; // Increment quantity if already in cart
    } else {
        $_SESSION['cart'][$productId] = ['quantity' => 1]; // Add new item with quantity 1
    }
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Clean up $_SESSION['cart'] to ensure no duplicates in structure
$cleanedCart = [];
foreach ($_SESSION['cart'] as $productId => $productInCart) {
    $productId = intval($productId); // Ensure productId is an integer
    $quantity = isset($productInCart['quantity']) ? intval($productInCart['quantity']) : 1;
    if ($quantity > 0) {
        $cleanedCart[$productId] = ['quantity' => $quantity];
    }
}
$_SESSION['cart'] = $cleanedCart;

// Get full product details (name, price, image) from DB for each cart item
$cartWithDetails = [];
$totalPrice = 0;

$productIds = array_keys($_SESSION['cart']);
if (!empty($productIds)) {
    // Prepare placeholders for SQL IN clause
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));

    $stmt = $mysqli->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
    // Bind parameters dynamically
    $types = str_repeat('i', count($productIds));
    $stmt->bind_param($types, ...$productIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }

    // Build cart with details, avoiding duplicates
    foreach ($_SESSION['cart'] as $productId => $productInCart) {
        if (isset($products[$productId])) {
            $product = $products[$productId];
            $quantity = $productInCart['quantity'];
            $price = floatval($product['price']);
            $subtotal = $price * $quantity;
            $totalPrice += $subtotal;

            // Check if the product is already in $cartWithDetails
            $found = false;
            foreach ($cartWithDetails as &$existingItem) {
                if ($existingItem['id'] == $productId) {
                    $existingItem['quantity'] += $quantity; // Update quantity
                    $found = true;
                    break;
                }
            }
            unset($existingItem); // Unset the reference

            // If not found, add as a new item
            if (!$found) {
                $cartWithDetails[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'image' => 'img/' . $product['image']
                ];
            }
        }
    }
} else {
    // Empty cart edge case
    header("Location: cart.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['buy_now'])) {
    $paymentMode = $_POST['payment_mode'] ?? '';

    if (!$paymentMode) {
        $error = "Please select a payment method.";
    } else {
        // COD delivery info validation
        if ($paymentMode === 'COD') {
            $deliveryName = trim($_POST['delivery_name'] ?? '');
            $deliveryAddress = trim($_POST['delivery_address'] ?? '');
            $deliveryContact = trim($_POST['delivery_contact'] ?? '');
            $deliveryEmail = trim($_POST['delivery_email'] ?? '');
            $deliveryNotes = trim($_POST['delivery_notes'] ?? '');

            if (!$deliveryName || !$deliveryAddress || !$deliveryContact || !$deliveryEmail) {
                $error = "Please fill in all delivery information including email.";
            } else {
                $_SESSION['delivery_info'] = [
                    'name' => $deliveryName,
                    'address' => $deliveryAddress,
                    'contact' => $deliveryContact,
                    'email' => $deliveryEmail,
                    'notes' => $deliveryNotes
                ];
            }
        }

        if (!$error) {
            $_SESSION['payment_mode'] = $paymentMode;

            // Save cart with quantities (as associative array productId => qty)
            $cartJson = $mysqli->real_escape_string(json_encode($_SESSION['cart']));
            $paymentModeEscaped = $mysqli->real_escape_string($paymentMode);
            $createdAt = date('Y-m-d H:i:s');
            $emailField = isset($_SESSION['delivery_info']['email']) ? "'" . $mysqli->real_escape_string($_SESSION['delivery_info']['email']) . "'" : "NULL";
            $status = 'pending'; // Set status to pending

            // Save order to DB
            $insertOrderSQL = "INSERT INTO orders (cart_json, total_price, payment_mode, order_date, email, status) 
                               VALUES ('$cartJson', $totalPrice, '$paymentModeEscaped', '$createdAt', $emailField, '$status')";

            if ($mysqli->query($insertOrderSQL)) {
                $orderId = $mysqli->insert_id;

                $_SESSION['order_details'] = [
                    'order_id' => $orderId,
                    'cart' => $cartWithDetails,
                    'total' => $totalPrice,
                    'payment_mode' => $paymentMode,
                    'status' => $status
                ];

                header("Location: " . ($paymentMode === 'COD' ? "cod.php" : "otc.php"));
                exit;
            } else {
                $error = "Failed to save order. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout - Furrfect Pawshop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    body { font-family: 'Montserrat', sans-serif; }
  </style>
</head>
<body class="bg-white text-black">
<div class="max-w-4xl mx-auto px-6 py-12">
  <h1 class="text-4xl font-light tracking-wide mb-8 text-yellow-800">FURRFECT PAWSHOP</h1>

  <nav class="text-sm text-gray-600 mb-8 font-light flex flex-wrap gap-2">
    <span>Cart</span><span>></span>
    <span class="font-semibold">Payment</span>
  </nav>

  <div class="flex flex-col lg:flex-row gap-10">
    <!-- Payment Form -->
    <div class="flex-1 bg-yellow-50 rounded-lg p-8 shadow-lg">
      <h2 class="text-xl font-semibold mb-6 text-yellow-900">Select Payment Mode</h2>
      <form method="post" class="space-y-6" id="payment-form">
        <label class="flex items-center gap-3 text-yellow-900 text-lg cursor-pointer">
          <input type="radio" name="payment_mode" value="COD" class="form-radio h-5 w-5" <?php if(isset($paymentMode) && $paymentMode == 'COD') echo "checked"; ?> required />
          Cash on Delivery (COD)
        </label>

        <label class="flex items-center gap-3 text-yellow-900 text-lg cursor-pointer">
          <input type="radio" name="payment_mode" value="Counter" class="form-radio h-5 w-5" <?php if(isset($paymentMode) && $paymentMode == 'Counter') echo "checked"; ?> required />
          Over-the-counter
        </label>

        <!-- Delivery Info -->
        <div id="delivery-info" class="space-y-4 bg-yellow-100 border border-yellow-300 p-4 rounded hidden">
          <h3 class="text-lg font-semibold text-yellow-900">Delivery Information</h3>
          <input type="text" name="delivery_name" placeholder="Full Name" class="w-full p-2 border border-yellow-300 rounded" />
          <input type="text" name="delivery_address" placeholder="Delivery Address" class="w-full p-2 border border-yellow-300 rounded" />
          <input type="text" name="delivery_contact" placeholder="Contact Number" class="w-full p-2 border border-yellow-300 rounded" />
          <input type="email" name="delivery_email" placeholder="Email Address" class="w-full p-2 border border-yellow-300 rounded" />
          <textarea name="delivery_notes" placeholder="Additional Notes (optional)" class="w-full p-2 border border-yellow-300 rounded"></textarea>
        </div>

        <?php if ($error): ?>
          <p class="text-red-600 text-sm font-medium"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <button type="submit" class="w-full mt-4 bg-yellow-700 hover:bg-yellow-800 text-white font-semibold py-3 rounded-lg transition">
          Place Order
        </button>
      </form>
    </div>

    <!-- Order Summary -->
    <div class="w-full lg:w-96 border-l border-yellow-300 pl-6">
      <h2 class="text-xl font-semibold mb-6 text-yellow-900">Order Summary</h2>
      <?php foreach ($cartWithDetails as $item): ?>
        <div class="flex items-center gap-4 mb-4">
          <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-14 h-14 object-cover rounded" />
          <div class="flex-1 text-sm leading-tight">
            <p class="font-semibold text-yellow-900"><?php echo htmlspecialchars($item['name']); ?></p>
            <p class="text-yellow-700">Qty: <?php echo (int)$item['quantity']; ?></p>
          </div>
          <div class="text-yellow-900 font-semibold">
            ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
          </div>
        </div>
      <?php endforeach; ?>

      <hr class="border-yellow-300 my-6" />
      <div class="flex justify-between text-yellow-900 font-semibold text-lg">
        <span>Total</span>
        <span>₱<?php echo number_format($totalPrice, 2); ?></span>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const codRadio = document.querySelector('input[value="COD"]');
    const counterRadio = document.querySelector('input[value="Counter"]');
    const deliveryInfo = document.getElementById("delivery-info");

    function toggleDeliveryFields() {
      deliveryInfo.classList.toggle("hidden", !codRadio.checked);
    }

    codRadio.addEventListener("change", toggleDeliveryFields);
    counterRadio.addEventListener("change", toggleDeliveryFields);
    toggleDeliveryFields(); // On load
  });
</script>
</body>
</html>
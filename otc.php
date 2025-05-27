<?php
session_start();
$order = $_SESSION['order_details'] ?? null;

if (!$order) {
    header("Location: main.php");
    exit;
}

function format_price($amount) {
    return number_format($amount, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Receipt - Over The Counter</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
body {
  font-family: 'Montserrat', sans-serif;
  background: #fff;
  color: #222;
  padding: 2rem;
}
.receipt-container {
  max-width: 600px;
  margin: auto;
  background: #fff9e5;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 0 15px rgba(255 223 93 / 0.5);
}
h1, h2 {
  color: #b77904;
}
ul {
  list-style: none;
  padding: 0;
}
ul li {
  border-bottom: 1px solid #d6ad00;
  padding: 0.5rem 0;
  display: flex;
  justify-content: space-between;
}
.no-print {
  margin-top: 1.5rem;
}
@media print {
  body {
    background: white !important;
    color: black !important;
    font-size: 12pt;
  }
  .no-print {
    display: none !important;
  }
  .receipt-container {
    box-shadow: none !important;
    width: 100% !important;
    margin: 0;
    border-radius: 0;
    padding: 0;
  }
}
</style>
</head>
<body>
<div class="receipt-container">
  <h1>Furrfect Pawshop</h1>
  <h2>Receipt</h2>
  <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['order_id']); ?></p>
  <p><strong>Payment Mode:</strong> <?php echo htmlspecialchars($order['payment_mode']); ?></p>
  <hr />
  <ul>
    <?php foreach ($order['cart'] as $item): ?>
      <li>
        <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo (int)$item['quantity']; ?></span>
        <span>₱<?php echo format_price($item['price'] * $item['quantity']); ?></span>
      </li>
    <?php endforeach; ?>
  </ul>
  <hr />
  <p style="text-align:right; font-weight:bold; font-size:1.2rem;">Total: ₱<?php echo format_price($order['total']); ?></p>
  <br>
  <h2 align="center">Thank you for Shopping may God Bless you 🙏🙏🙏.<br>Please Proceed to Counter</h2>
  <div class="no-print flex justify-center gap-4 mt-6">
    <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Print Receipt</button>
    <a href="main.php" class="bg-yellow-700 text-white px-4 py-2 rounded hover:bg-yellow-800">Back to Home</a>
  </div>
</div>
</body>
</html>
<?php
// Clear cart after showing receipt
unset($_SESSION['cart']);
?>
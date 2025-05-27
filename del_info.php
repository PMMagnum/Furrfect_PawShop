<?php
// Start session and include config
session_start(); // Ensure the session is started before using session variables
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: del_info.php'); // Redirect to login if not logged in
    exit();
}

// Check if the user is an admin and prevent access
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: admin_dashboard.php'); // Redirect to admin dashboard if user is an admin
    exit();
}

// Fetch delivery information (Ideally from the database)
$user_id = $_SESSION['user_id'];

// For demonstration, here's a static delivery info array
$delivery_info = [
    'name' => 'John Doe',
    'address' => '1234 Paw Street, Purrtown',
    'city' => 'Purrsylvania',
    'state' => 'PS',
    'zip' => '12345',
    'phone' => '123-456-7890',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!-- Include your Navbar -->
<?php include 'navbar.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Delivery Information</h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($delivery_info['name']); ?></h5>
            <p class="card-text">
                <strong>Address:</strong> <?php echo htmlspecialchars($delivery_info['address']); ?><br>
                <strong>City:</strong> <?php echo htmlspecialchars($delivery_info['city']); ?><br>
                <strong>State:</strong> <?php echo htmlspecialchars($delivery_info['state']); ?><br>
                <strong>ZIP Code:</strong> <?php echo htmlspecialchars($delivery_info['zip']); ?><br>
                <strong>Phone:</strong> <?php echo htmlspecialchars($delivery_info['phone']); ?>
            </p>
        </div>
    </div>

    <div class="mt-4">
        <a href="edit_del_info.php" class="btn btn-primary">Edit Delivery Info</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

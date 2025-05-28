<?php
// Start the session (if needed)
session_start();

// Database connection details
$servername = "localhost";
$username = "u866427573_furfect";      // Change if your MySQL username is different
$password = "@Qetu1357";          // Use your password here if applicable (e.g., 'root' or 'password')
$dbname = "u866427573_furfect";  // Updated to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Show a specific error message
}

// Pagination variables
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Initialize filter variables
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the SQL query based on filters
$query = "SELECT order_id, cart_items, payment_mode, total, created_at FROM receipts WHERE 1";

// Apply filters
if ($start_date && $end_date) {
    $query .= " AND created_at BETWEEN ? AND ?";
}

// Add pagination
$query .= " LIMIT ? OFFSET ?";

// Prepare and execute the statement
$stmt = $conn->prepare($query);

// Bind parameters based on filter types
if ($start_date && $end_date) {
    $stmt->bind_param('ssii', $start_date, $end_date, $limit, $offset);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}

// Execute the statement and get results
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include('header.php'); ?>

<!-- Main content -->
<div class="bg-white p-6 rounded-lg shadow-md">

    <!-- Page title -->
    <h2 class="text-3xl font-bold text-yellow-800 mb-4">Shopping History</h2>

    <!-- Filter form -->
    <form method="get" action="shopping_history.php" class="mb-6">
        <div class="flex space-x-4">
            <div>
                <label for="start_date" class="block text-lg font-semibold">Start Date:</label>
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="border border-gray-300 p-2 rounded-md">
            </div>
            <div>
                <label for="end_date" class="block text-lg font-semibold">End Date:</label>
                <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="border border-gray-300 p-2 rounded-md">
            </div>
            <div class="flex items-end">
                <input type="submit" value="Filter" class="bg-yellow-600 text-white px-6 py-2 rounded-md cursor-pointer hover:bg-yellow-700">
            </div>
        </div>
    </form>

    <!-- Display records -->
    <?php if ($result->num_rows > 0): ?>
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-yellow-200 text-yellow-800">
                    <th class="px-4 py-2 text-left">Order ID</th>
                    <th class="px-4 py-2 text-left">Cart Items</th>
                    <th class="px-4 py-2 text-left">Payment Mode</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // Decode the JSON-encoded 'cart_items' column
                    $cart_items = json_decode($row['cart_items'], true);
                    $cart_items_str = $cart_items ? implode(", ", array_column($cart_items, 'name')) : "No items";
                    ?>
                    <tr class="border-t hover:bg-yellow-50">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($cart_items_str); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['payment_mode']); ?></td>
                        <td class="px-4 py-2">₱<?php echo number_format($row['total'], 2); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php
        // Get the total number of records for pagination
        $countQuery = "SELECT COUNT(*) AS total FROM receipts WHERE 1";
        if ($start_date && $end_date) {
            $countQuery .= " AND created_at BETWEEN ? AND ?";
        }

        $countStmt = $conn->prepare($countQuery);

        if ($start_date && $end_date) {
            $countStmt->bind_param('ss', $start_date, $end_date);
        }

        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $countRow = $countResult->fetch_assoc();
        $totalRecords = $countRow['total'];

        // Calculate total pages
        $totalPages = ceil($totalRecords / $limit);
        ?>

        <div class="mt-6 flex justify-center space-x-4">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo ($page - 1); ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo ($page + 1); ?>&start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Next</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-lg text-yellow-800 mt-6">No records found.</p>
    <?php endif; ?>

</div>

</body>
</html>

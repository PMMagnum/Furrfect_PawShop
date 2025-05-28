<?php
session_start();

// Check if user is logged in and receipt data exists
if (!isset($_SESSION['user']) || !isset($_SESSION['receipt'])) {
    header('Location: pos.php');
    exit();
}

$user = $_SESSION['user'];

// Handle clear receipt action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_receipt'])) {
    unset($_SESSION['receipt']);
    header('Location: pos.php');
    exit();
}

// Calculate VAT-able Sales and VAT Amount from Total (Total includes VAT)
$total_with_vat = $_SESSION['receipt']['total']; // Total includes VAT
$vatable_sales = $total_with_vat / 1.12; // VAT-able Sales
$vat_amount = $total_with_vat - $vatable_sales; // VAT Amount

// Additional fields as per the image
$vat_exempt_sales = 0.00; // Assuming 0 for now, can be updated if available in session
$vat_zero_rated_sales = 0.00; // Assuming 0 for now
$non_taxable_sales = 0.00; // Assuming 0 for now
$amount_payable = $total_with_vat; // Amount Payable is the same as Total in this case
$transaction_number = 243768; // Hardcoded as per image, can be dynamic if stored in session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Furrfect Pawshop Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 10px;
            box-sizing: border-box;
        }
        .receipt-container {
            background: white;
            width: 80mm;
            max-width: 300px;
            padding: 8px;
            font-size: 10px;
            line-height: 1.3;
            border: 1px solid #000;
            box-shadow: none;
            box-sizing: border-box;
        }
        .receipt-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 3px;
        }
        .receipt-logo {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }
        .receipt-container h3 {
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            margin: 0;
            text-transform: uppercase;
        }
        .receipt-container p {
            margin: 2px 0;
            text-align: center;
        }
        .totals-section p {
            text-align: left; /* Align totals to the left */
            margin: 2px 0;
        }
        .receipt-container table {
            width: 100%;
            margin: 3px 0;
            border-collapse: collapse;
        }
        .receipt-container th, .receipt-container td {
            padding: 1px 0;
            text-align: left;
            font-size: 10px;
        }
        .receipt-container th {
            border-bottom: 1px dashed #000;
        }
        .receipt-container .item-name {
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .receipt-container .btn-print, .receipt-container .btn-close-receipt {
            width: 48%;
            margin: 3px 1%;
            padding: 4px;
            font-size: 10px;
            border: none;
            border-radius: 2px;
            color: white;
            cursor: pointer;
            display: inline-block;
        }
        .btn-print {
            background-color: #2a9d8f;
        }
        .btn-print:hover {
            background-color: #21867a;
        }
        .btn-close-receipt {
            background-color: #e74c3c;
        }
        .btn-close-receipt:hover {
            background-color: #c0392b;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 3px 0;
        }
        @media print {
            @page {
                size: 80mm 230mm;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                min-height: unset;
            }
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: static;
                width: 80mm;
                max-width: 80mm;
                min-height: auto;
                border: none;
                padding: 8px;
                margin: 0;
            }
            .receipt-container .btn-print, .receipt-container .btn-close-receipt {
                display: none;
            }
            .receipt-logo {
                filter: grayscale(100%);
            }
        }
        @media (max-width: 768px) {
            .receipt-container {
                width: 100%;
                max-width: 260px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <img src="images\finallogo.png" alt="Furrfect Pawshop Logo" class="receipt-logo" />
            <h3>Furrfect Pawshop</h3>
        </div>
        <p>Poblacion Liloan, Liloan Cebu</p>
        <p>Vat Reg TIN: 000-071-054-000</p>
        <p>PTU# FP122023-080-036&071-00000</p>
        <p>PTU Issued: 03/17/2023</p>
        <p>POS SN: 10002104061</p>
        <p>MIN: 230181015415970</p>
        <p style="text-align: center;">--- Receipt ---</p>
        <p>Receipt No: <?= htmlspecialchars($_SESSION['receipt']['receipt_number']) ?></p>
        <p>Date: <?= htmlspecialchars($_SESSION['receipt']['date']) ?></p>
        <p>Cashier: <?= htmlspecialchars($user['username']) ?></p>
        <div class="divider"></div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['receipt']['items'] as $item): ?>
                    <tr>
                        <td class="item-name"><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="divider"></div>
        <div class="totals-section">
            <h3 style="text-align: right;"><strong>TOTAL:</strong> ₱<?= number_format($total_with_vat, 2) ?></h3>
            <p style="text-align: right;"><strong>CASH:</strong> ₱<?= number_format($_SESSION['receipt']['cash'], 2) ?></>
            <h3 style="text-align: right;"><strong>CHANGE:</strong> ₱<?= number_format($_SESSION['receipt']['cash'] - $total_with_vat, 2) ?></h3>
            <div class="divider"></div>
            <p><strong>VAT SALES:</strong> ₱<?= number_format($vatable_sales, 2) ?></p>
            <p><strong>VAT EXEMPT SALES:</strong> ₱<?= number_format($vat_exempt_sales, 2) ?></p>
            <p><strong>VAT ZERO-RATED SALES:</strong> ₱<?= number_format($vat_zero_rated_sales, 2) ?></p>
            <p><strong>NON-TAXABLE SALES:</strong> ₱<?= number_format($non_taxable_sales, 2) ?></p>
            <p><strong>VAT (12%):</strong> ₱<?= number_format($vat_amount, 2) ?></p>
            <p><strong>AMOUNT PAYABLE:</strong> ₱<?= number_format($amount_payable, 2) ?></p>
        </div>
        <div class="divider"></div>
        <p style="text-align: center;">(888) 432-8900</p>
        <p style="text-align: center;">furrfectpawshop@gmail.com</p>
        <p style="text-align: center;">Thank You for Shopping! AW MEOW</p>
        <form method="POST" style="display: inline;">
            <button type="button" class="btn btn-print" onclick="printReceipt()">Print Receipt</button>
            <button type="submit" name="clear_receipt" class="btn btn-close-receipt">Close</button>
        </form>
    </div>
    <script>
        function printReceipt() {
            window.print();
            setTimeout(() => {
                try {
                    window.onafterprint = () => {
                        // Optionally redirect or clear after printing
                    };
                } catch (e) {
                    console.log('Automatic print dialog close not supported');
                }
            }, 100);
        }
    </script>
</body>
</html>
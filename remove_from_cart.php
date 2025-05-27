<?php
session_start();
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}
header('Location: main.php');
exit;
?>
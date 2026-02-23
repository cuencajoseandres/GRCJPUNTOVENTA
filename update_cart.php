<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] = $qty;
    }
}
header('Location: cart.php');
exit;
?>
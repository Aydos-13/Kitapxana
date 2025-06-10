<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
$cart_item_id = $_POST['cart_item_id'] ?? null;
if ($cart_item_id) {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ?");
    $stmt->execute([$cart_item_id]);
}
header("Location: cart.php");
exit();
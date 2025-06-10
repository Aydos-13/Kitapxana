<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT ci.*, b.price
                       FROM cart_items ci
                       JOIN books b ON ci.book_id = b.id
                       WHERE ci.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
if (empty($cart_items)) {
    die("Ваша корзина пуста.");
}
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['quantity'] * $item['price'];
}
$stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, order_date, created_at) VALUES (?, ?, 'pending', NOW(), NOW())");
$stmt->execute([$user_id, $total_price]);
$order_id = $pdo->lastInsertId();
foreach ($cart_items as $item) {
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $order_id,
        $item['book_id'],
        $item['quantity'],
        $item['price']
    ]);
    $updateStmt = $pdo->prepare("UPDATE books SET quantity = quantity - ? WHERE id = ?");
    $updateStmt->execute([$item['quantity'], $item['book_id']]);
}
$stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
$stmt->execute([$user_id]);
header("Location: my_orders.php");
exit();
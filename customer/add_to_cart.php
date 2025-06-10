<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$book_id = $_GET['book_id'];
$quantity = 1;
$stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? AND book_id = ?");
$stmt->execute([$user_id, $book_id]);
$item = $stmt->fetch();
if ($item) {
    $_SESSION['message'] = "Bul kitap álleqashan sebette bar.";
    header("Location: ../index.php");
    exit();
} else {
    $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, book_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $book_id, $quantity]);
    header("Location: cart.php");
    exit();
}
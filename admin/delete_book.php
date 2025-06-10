<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$book_id]);
        header("Location: manage_books.php");
        exit();
    } catch (PDOException $e) {
        header("Location: manage_books.php?error=book_in_use");
        exit();
    }
}
?>

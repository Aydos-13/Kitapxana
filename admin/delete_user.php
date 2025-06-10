<?php
require_once '../includes/db.php';
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_SESSION['user']['id'] != $id) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
header("Location: manage_users.php");
exit();
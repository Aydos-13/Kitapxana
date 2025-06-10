<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$genre_name = '';
$edit = false;
if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $pdo->prepare("SELECT * FROM genres WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $genre = $stmt->fetch();
    if ($genre) {
        $genre_name = $genre['name'];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if ($edit) {
        $stmt = $pdo->prepare("UPDATE genres SET name = ? WHERE id = ?");
        $stmt->execute([$name, $_GET['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO genres (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    header("Location: manage_genres.php");
    exit();
}
include '../includes/header.php';
?>
<div class="container py-5 p-5">
    <div class="mx-auto bg-white shadow-sm rounded-4 p-5" style="max-width: 600px;">
        <h3 class="mb-4 text-center text-primary"><?= $edit ? 'Janrdı ózgertiriw' : 'Jańa janr qosıw' ?></h3>
        <form method="post">
            <div class="mb-4">
                <label for="name" class="form-label fw-semibold">Janr atı</label>
                <input type="text" name="name" id="name" class="form-control rounded-3" required value="<?= htmlspecialchars($genre_name) ?>">
            </div>
            <div class="d-flex justify-content-between">
                <a href="manage_genres.php" class="btn btn-outline-secondary rounded-3 px-4">Artqa</a>
                <button type="submit" class="btn btn-success rounded-3 px-4"><?= $edit ? 'Saqlaw' : 'Qosıw' ?></button>
            </div>
        </form>
    </div>
</div>
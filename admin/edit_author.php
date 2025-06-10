<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$author_name = '';
$edit = false;

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $pdo->prepare("SELECT * FROM authors WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $author = $stmt->fetch();
    if ($author) {
        $author_name = $author['name'];
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if ($edit) {
        $stmt = $pdo->prepare("UPDATE authors SET name = ? WHERE id = ?");
        $stmt->execute([$name, $_GET['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO authors (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    header("Location: manage_authors.php");
    exit();
}
include '../includes/header.php';
?>
<div class="container py-5 p-5">
    <div class="mx-auto bg-white shadow-sm rounded-4 p-5" style="max-width: 600px;">
        <h3 class="mb-4 text-center text-primary"><?= $edit ? 'Avtordı ózgertiriw' : 'Jańa avtor qosıw' ?></h3>
        <form method="post">
            <div class="mb-4">
                <label for="name" class="form-label fw-semibold">Avtor atı</label>
                <input type="text" name="name" id="name" class="form-control rounded-3" required value="<?= htmlspecialchars($author_name) ?>">
            </div>
            <div class="d-flex justify-content-between">
                <a href="manage_authors.php" class="btn btn-outline-secondary rounded-3 px-4">Artqa</a>
                <button type="submit" class="btn btn-success rounded-3 px-4"><?= $edit ? 'Saqlaw' : 'Júklew' ?></button>
            </div>
        </form>
    </div>
</div>


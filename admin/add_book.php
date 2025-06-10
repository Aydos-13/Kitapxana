<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$authors = $pdo->query("SELECT id, name FROM authors")->fetchAll();
$genres = $pdo->query("SELECT id, name FROM genres")->fetchAll();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $author_id = $_POST['author_id'] ?? '';
    $genre_id = $_POST['genre_id'] ?? '';
    $description = $_POST['description'] ?? '';
    $image_path = '';
    if ($title && $price > 0 && $quantity > 0 && $author_id && $genre_id) {
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = '../assets/images/';
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = '/assets/images/' . $filename;
            }
        }
        $stmt = $pdo->prepare("INSERT INTO books (title, price, quantity, author_id, genre_id, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $price, $quantity, $author_id, $genre_id, $description, $image_path]);

        $message = '<div class="alert alert-success">Kitap tabıslı qosıldı!</div>';
    } else {
        $message = '<div class="alert alert-danger">Iltimas hámme maydanlardı toltırıń!</div>';
    }
}
include '../includes/header.php';
?>
<div class="container mt-5 p-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-book-plus"></i> Jańa kitap  qosıw</h4>
        </div>
        <div class="card-body bg-light">
            <?= $message ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">📘 Kitap atı</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">💰 Baxası (swm)</label>
                        <input type="number" name="price" id="price" class="form-control" required min="1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">📦 Muǵdarı</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="author_id" class="form-label">✍️ Avtor</label>
                    <select name="author_id" id="author_id" class="form-select" required>
                        <option value="">Avtordı saylań</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= $author['id'] ?>"><?= htmlspecialchars($author['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="genre_id" class="form-label">🎭 Janr</label>
                    <select name="genre_id" id="genre_id" class="form-select" required>
                        <option value="">Janrni tanlang</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>"><?= htmlspecialchars($genre['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">🖼️ Súwret júklew</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">📝 Túsindirme</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Kitap haqqında qısqasha..."></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill">
                        <i class="bi bi-check-circle-fill"></i> Kitaptı qosıw
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
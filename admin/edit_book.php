<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    header("Location: books.php");
    exit();
}

// Kitapni bazadan olish
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    echo "<div class='alert alert-danger'>Kitap tabılmadı!</div>";
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
    $image_path = $book['image'];

    if ($title && $price > 0 && $quantity > 0 && $author_id && $genre_id) {
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = '../assets/images/';
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $target_path = $upload_dir . $filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = '/assets/images/' . $filename;
            }
        }

        $stmt = $pdo->prepare("UPDATE books SET title = ?, price = ?, quantity = ?, author_id = ?, genre_id = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$title, $price, $quantity, $author_id, $genre_id, $description, $image_path, $book_id]);

        $message = '<div class="alert alert-success">Kitap tabıslı ózgertildi!</div>';
        // Update qilishdan keyin qayta o‘qish
        $book['title'] = $title;
        $book['price'] = $price;
        $book['quantity'] = $quantity;
        $book['author_id'] = $author_id;
        $book['genre_id'] = $genre_id;
        $book['description'] = $description;
        $book['image'] = $image_path;
    } else {
        $message = '<div class="alert alert-danger">Iltimas, hámme maydanlardı toltırıń!</div>';
    }
}

include '../includes/header.php';
?>
<div class="container mt-5 p-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-warning text-dark py-3 rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Kitap ózgertiw</h4>
        </div>
        <div class="card-body bg-light">
            <?= $message ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">📘 Kitap atı</label>
                    <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($book['title']) ?>">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">💰 Baxası (swm)</label>
                        <input type="number" name="price" id="price" class="form-control" required min="1" value="<?= $book['price'] ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">📦 Muǵdarı</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required min="1" value="<?= $book['quantity'] ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="author_id" class="form-label">✍️ Avtor</label>
                    <select name="author_id" id="author_id" class="form-select" required>
                        <option value="">Avtordı saylań</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= $author['id'] ?>" <?= $book['author_id'] == $author['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($author['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="genre_id" class="form-label">🎭 Janr</label>
                    <select name="genre_id" id="genre_id" class="form-select" required>
                        <option value="">Janrni tanlang</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= $book['genre_id'] == $genre['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genre['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">🖼️ Súwret júklew (ixtiyoriy)</label>
                    <input type="file" name="image" id="image" class="form-control">
                    <?php if ($book['image']): ?>
                        <img src="<?= $book['image'] ?>" alt="Mavjud rasm" class="mt-2 rounded" style="max-height: 120px;">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">📝 Túsindirme</label>
                    <textarea name="description" id="description" class="form-control" rows="3"><?= htmlspecialchars($book['description']) ?></textarea>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-warning btn-lg rounded-pill">
                        <i class="bi bi-save"></i> Ózgertiwdi saqlaw
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

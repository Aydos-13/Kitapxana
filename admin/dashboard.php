<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

requireLogin();

if (!isAdmin()) {
    header("Location: ../login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT books.*, authors.name AS author_name, genres.name AS genre_name
    FROM books
    JOIN authors ON books.author_id = authors.id
    JOIN genres ON books.genre_id = genres.id
    ORDER BY books.id DESC
");

$books = $stmt->fetchAll();

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content px-4 py-5 bg-light rounded">
    <h2 class="text-center mb-3 fw-bold text-primary">Administrator bólimi</h2>
    <h4 class="text-center mb-4 text-secondary">Kitaplar katalogı</h4>
    <div class="row g-4">
        <?php if ($books): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow border-0 rounded-4 hover-shadow">
                        <?php if (!empty($book['image'])): ?>
                            <img src="<?= htmlspecialchars($book['image']) ?>" 
                                 class="card-img-top rounded-top-4" 
                                 alt="<?= htmlspecialchars($book['title']) ?>" 
                                 style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                            <img src="/assets/images/default.jpg" 
                                 class="card-img-top rounded-top-4" 
                                 alt="Rasm mavjud emas" 
                                 style="height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title text-center text-dark"><?= htmlspecialchars($book['title']) ?></h5>
                            <ul class="list-unstyled text-muted small">
                                <li><strong>Avtor:</strong> <?= htmlspecialchars($book['author_name']) ?></li>
                                <li><strong>Janr:</strong> <?= htmlspecialchars($book['genre_name']) ?></li>
                                <li><strong>Sanı:</strong> <?= $book['quantity'] ?></li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge bg-success fs-6"><?= number_format($book['price'], 2) ?> swm</span>
                                <div class="btn-group btn-group-sm">
                                    <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil-square"></i> ozgertiw
                                    </a>
                                    <a href="delete_book.php?id=<?= $book['id'] ?>" class="btn btn-danger" onclick="return confirm('Rastanda oshiresizbe?');">
                                        <i class="bi bi-trash"></i> oshiriw
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">Házirshe hesh qanday kitap qosılmaǵan</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0 15px rgba(0,0,0,0.15) !important;
        transition: box-shadow 0.3s ease-in-out;
    }
</style>

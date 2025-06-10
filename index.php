<?php
session_start();
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info text-center">
        <?= $_SESSION['message'] ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<?php
require_once 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("
    SELECT books.*, authors.name AS author_name, genres.name AS genre_name
    FROM books
    JOIN authors ON books.author_id = authors.id
    JOIN genres ON books.genre_id = genres.id
    ORDER BY books.id DESC
");
$books = $stmt->fetchAll();
?>

<div class="container mt-5 pt-5 py-5 bg-light min-vh-100 ">
    <h2 class="mb-5 text-center text-primary fw-bold">📚 Kitaplar katalogı</h2>
    <div class="row g-4">
        <?php if ($books): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <?php if (!empty($book['image'])): ?>
                            <img src="<?= htmlspecialchars($book['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 280px; object-fit: cover;">
                        <?php else: ?>
                            <img src="assets/images/default.jpg" class="card-img-top" alt="Súwret joq" style="height: 280px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text text-muted mb-1">
                                <strong>Avtor:</strong> <?= htmlspecialchars($book['author_name']) ?><br>
                                <strong>Janr:</strong> <?= htmlspecialchars($book['genre_name']) ?>
                            </p>
                            <p class="card-text text-success fw-bold mt-auto">Baxası: <?= number_format($book['price'], 2) ?> swm</p>
                            <a href="customer/add_to_cart.php?book_id=<?= $book['id'] ?>" class="btn btn-primary w-100 mt-3 rounded-pill">📥 Sebetke qosıw</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center rounded-3 shadow-sm">
                    Házirshe hesh qanday kitap qosılmaǵan.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

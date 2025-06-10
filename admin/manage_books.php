<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$stmt = $pdo->query("
    SELECT b.id, b.title, a.name AS author_name,b.quantity, g.name AS genre_name, b.price 
    FROM books b
    LEFT JOIN authors a ON b.author_id = a.id
    LEFT JOIN genres g ON b.genre_id = g.id
    ORDER BY b.id DESC
");
$books = $stmt->fetchAll();
?>
<?php
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">Kitaplardı basqarıw</h2>
        <a href="add_book.php" class="btn btn-success rounded-3">+ Jańa kitap qosıw</a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'book_in_use'): ?>
        <div class="alert alert-danger text-center shadow-sm rounded-3">
            Kitaptı óshiriwdiń imkani joq, sebebi ol buyırtpalarda paydalanılıp atır.
        </div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 25%;">Atı</th>
                        <th style="width: 15%;">Avtor</th>
                        <th style="width: 15%;">Janr</th>
                        <th style="width: 10%;">Sanı</th>
                        <th style="width: 15%;">Baxası</th>
                        <th style="width: 15%;" class="text-end">Háreket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= $book['id'] ?></td>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author_name']) ?></td>
                            <td><?= htmlspecialchars($book['genre_name']) ?></td>
                            <td><?= htmlspecialchars($book['quantity']) ?></td>
                            <td><?= number_format($book['price'], 2) ?> so‘m</td>
                            <td class="text-end">
                                <a href="delete_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Bul kitaptı óshiriwdi qaleysiz be?')">Óshiriw</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Házirshe hesh qanday kitap joq.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
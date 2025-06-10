<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['delete'])) {
    $author_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM authors WHERE id = ?");
        $stmt->execute([$author_id]);
        header("Location: manage_authors.php");
        exit();
    } catch (PDOException $e) {
        $error = "Avtordı óshiriwdiń imkani joq — sebebi ol kitaplar menen baylanısqan.";
    }
}
$stmt = $pdo->query("SELECT * FROM authors");
$authors = $stmt->fetchAll();
?>
<?php
include '../includes/header.php';
include '../includes/sidebar.php';
?>

<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">Avtorlardı basqarıw</h2>
        <a href="edit_author.php" class="btn btn-success rounded-3">+ Jańa avtor</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 60%;">Avtor atı</th>
                        <th style="width: 30%;" class="text-end">Háreket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authors as $author): ?>
                        <tr>
                            <td><?= $author['id'] ?></td>
                            <td><?= htmlspecialchars($author['name']) ?></td>
                            <td class="text-end">
                                <a href="edit_author.php?id=<?= $author['id'] ?>" class="btn btn-sm btn-outline-primary rounded-3 me-2">Ózgertiriw</a>
                                <a href="manage_authors.php?delete=<?= $author['id'] ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Bul avtordı óshiriwdi qaleysiz be?')">Óshiriw</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($authors)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Hesh qanday avtor tabılmadı.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
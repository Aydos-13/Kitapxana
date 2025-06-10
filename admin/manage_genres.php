<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
if (isset($_GET['delete'])) {
    $genre_id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM genres WHERE id = ?");
        $stmt->execute([$genre_id]);
        header("Location: manage_genres.php");
        exit();
    } catch (PDOException $e) {
        $error = "Janrdı óshiriwdiń imkani joq — sebebi ol kitaplar menen baylanısqan.";
    }
}
$stmt = $pdo->query("SELECT * FROM genres");
$genres = $stmt->fetchAll();
include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">Janrlardı basqarıw</h2>
        <a href="edit_genre.php" class="btn btn-success rounded-3">+ Jańa janr qosıw</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm rounded-3"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 60%;">Janr atı</th>
                        <th style="width: 30%;" class="text-end">Háreket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genres as $genre): ?>
                        <tr>
                            <td><?= $genre['id'] ?></td>
                            <td><?= htmlspecialchars($genre['name']) ?></td>
                            <td class="text-end">
                                <a href="edit_genre.php?id=<?= $genre['id'] ?>" class="btn btn-sm btn-outline-primary rounded-3 me-2">Ózgertiriw</a>
                                <a href="manage_genres.php?delete=<?= $genre['id'] ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Bul janrdı óshiriwdi qaleysiz be?')">Óshiriw</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($genres)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Házirshe hesh qanday janr joq</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
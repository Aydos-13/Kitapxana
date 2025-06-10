<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$stmt = $pdo->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4">
    <h2 class="mb-4 text-primary">Paydalanıwshılardı basqarıw</h2>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 20%;">Atı</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 30%;">Roli</th>
                        <th style="width: 20%;" class="text-end">Háreket</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <form action="update_role.php" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" class="form-select form-select-sm me-2 w-auto rounded-3 shadow-sm">
                                    <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Qariydar</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-3">Saqlaw</button>
                            </form>
                        </td>
                        <td class="text-end">
                            <?php if ($_SESSION['user']['id'] !== $user['id']): ?>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('Paydalanıwshını óshiriwdi qaleysiz be?')">Óshiriw</a>
                            <?php else: ?>
                                <span class="badge bg-secondary">Siz</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Hesh qanday paydalanıwshı joq.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
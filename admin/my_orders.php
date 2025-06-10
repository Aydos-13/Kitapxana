<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.total_price, o.created_at, o.status, u.name AS buyer_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON oi.order_id = o.id
    JOIN books b ON oi.book_id = b.id
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->fetchAll();
include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="main-content p-4">
    <h2 class="mb-4 text-primary">Buyurtpalar dizimi</h2>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center shadow-sm rounded-3">
            Sizde hesh qanday buyurtpa joq.
        </div>
    <?php else: ?>
        <div class="card shadow-sm rounded-4">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Qariydar</th>
                            <th style="width: 15%;">Jámi summa</th>
                            <th style="width: 20%;">Buyurtpa sánesi</th>
                            <th style="width: 20%;">Jaǵdayı</th>
                            <th style="width: 15%;" class="text-end">Háreket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                <td><?= number_format($order['total_price'], 2) ?> swm</td>
                                <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <?php if ($order['status'] == 'pending'): ?>
                                        <span class="badge bg-primary">Kútilmekte</span>
                                    <?php elseif ($order['status'] == 'shipped'): ?>
                                        <span class="badge bg-warning text-dark">Jetkizilmekte</span>
                                    <?php elseif ($order['status'] == 'completed'): ?>
                                        <span class="badge bg-success">Juwmaqlandı</span>
                                    <?php elseif ($order['status'] == 'error'): ?>
                                        <span class="badge bg-danger">Biykar qılındı</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belgisiz</span>
                                    <?php endif ?>
                                </td>
                                <td class="text-end">
                                    <a href="order_details.php?id=<?= $order['order_id'] ?>" class="btn btn-sm btn-outline-primary rounded-3">Kóbirek</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
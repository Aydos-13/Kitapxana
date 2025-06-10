<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT o.id, o.created_at, o.status,
           (SELECT SUM(b.price * oi.quantity)
            FROM order_items oi
            JOIN books b ON b.id = oi.book_id
            WHERE oi.order_id = o.id) as total
    FROM orders o
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
include '../includes/header.php';
?>
<div class="container mt-5 p-5">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h2 class="mb-4 text-center text-primary fw-bold">📦 Buyurtpalarım</h2>
        <?php if (count($orders) === 0): ?>
            <div class="alert alert-warning text-center">Siz ele buyurtpa bermegensiz.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-borderless align-middle text-center">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#ID</th>
                            <th>Sáne</th>
                            <th>Ulıwmalıq summa</th>
                            <th>Jaǵdayı</th>
                            <th>Háreket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['id'] ?></strong></td>
                                <td><?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></td>
                                <td><?= number_format($order['total'], 2) ?> swm</td>
                                <td>
                                    <?php
                                        $status = $order['status'];
                                        $badgeClass = match($status) {
                                            'pending' => 'warning',
                                            'shipped' => 'info',
                                            'completed' => 'success',
                                            'error' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusText = match($status) {
                                            'pending' => '⏳ Kútilmekte',
                                            'shipped' => '🚚 Jetkizilmekte',
                                            'completed' => '✅ Juwmaqlandı',
                                            'error' => '❌ Biykar qılındı',
                                            default => 'Belgisiz'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badgeClass ?> px-3 py-2"><?= $statusText ?></span>
                                </td>
                                <td>
                                    <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill">
                                        🔍 Tolıǵıraq
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

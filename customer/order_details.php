<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    echo "Noto‘g‘ri buyurtma IDsi.";
    exit();
}
$stmt = $pdo->prepare("
    SELECT o.*, u.name AS customer_name, u.address
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) {
    echo "Buyurtma topilmadi.";
    exit();
}
$stmt = $pdo->prepare("
    SELECT b.title, b.price, oi.quantity
    FROM order_items oi
    JOIN books b ON b.id = oi.book_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
include '../includes/header.php';
?>
<div class="container mt-5 p-5">
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <h3 class="text-center mb-4 text-primary">📄 Buyurtpa haqqında tolıǵıraq #<?= $order_id ?></h3>

        <div class="row mb-4">
            <div class="col-md-4">
                <p><strong>Sáne:</strong><br> <?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Mánzil:</strong><br> <?= htmlspecialchars($order['address']) ?></p>
            </div>
            <div class="col-md-4">
                <?php
                    $statusClass = match($order['status']) {
                        'pending' => 'warning',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'error' => 'danger',
                        default => 'secondary'
                    };
                    $statusLabel = match($order['status']) {
                        'pending' => '⏳ Kútilmekte',
                        'shipped' => '🚚 Jetkizilmekte',
                        'completed' => '✅ Juwmaqlandı',
                        'error' => '❌ Biykar qılındı',
                        default => 'Belgisiz'
                    };
                ?>
                <p><strong>Jaǵdayı:</strong><br>
                    <span class="badge bg-<?= $statusClass ?> px-3 py-2"><?= $statusLabel ?></span>
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-borderless rounded-3 overflow-hidden align-middle">
                <thead class="bg-secondary text-white">
                    <tr>
                        <th>📘 Kitap atı</th>
                        <th class="text-center">Sanı</th>
                        <th class="text-end">Baxası (dana)</th>
                        <th class="text-end">Jámi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($items as $item):
                        $subtotal = $item['quantity'] * $item['price'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end"><?= number_format($item['price'], 2) ?> swm</td>
                            <td class="text-end"><?= number_format($subtotal, 2) ?> swm</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <td colspan="3" class="text-end fw-bold">Ulıwmalıq summa:</td>
                        <td class="text-end fw-bold text-success"><?= number_format($total, 2) ?> swm</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

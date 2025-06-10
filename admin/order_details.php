<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    echo "Buyurtpa IDı tabılmadı!.";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt->execute([$new_status, $order_id])) {
        echo "<div class='alert alert-success'>Buyurtpa jaǵdayı tabıslı qosıldı!</div>";
    } else {
        echo "<div class='alert alert-danger'>Buyurtpa jaǵdayın jańalawda qatelik!</div>";
    }
}
$stmt = $pdo->prepare("
SELECT o.*, u.name AS buyer_name, u.address
FROM orders o
JOIN users u ON o.user_id = u.id
WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) {
    echo "Buyurtpa tabılmadı!";
    exit();
}
$stmt = $pdo->prepare("
SELECT b.title, oi.quantity, b.price
FROM order_items oi
JOIN books b ON oi.book_id = b.id
WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$books = $stmt->fetchAll();
include '../includes/header.php';
?>
<?php include '../includes/header.php'; ?>

<div class="container my-5 p-5">
    <h3 class="mb-4 text-primary">Buyurtpa #<?= $order_id ?> tolıq maǵlıwmatları</h3>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if ($stmt->rowCount()): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Buyurtpa jaǵdayı tabıslı jańalandı
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Jawıw"></button>
            </div>
        <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Buyurtpa jaǵdayın jańalawda qatelik!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Jawıw"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <p><strong>Qariydar:</strong> <?= htmlspecialchars($order['buyer_name']) ?></p>
            <p><strong>Mánzil:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Sáne:</strong> <?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></p>

            <form method="POST" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="status" class="form-label">Buyurtpa jaǵdayın ózgertiriw:</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Kútilmekte</option>
                        <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Jetkizilmekte</option>
                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Juwmaqlandı</option>
                        <option value="error" <?= $order['status'] == 'error' ? 'selected' : '' ?>>Biykar qılındı</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-success w-100">Jańalaw</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Buyurtpadaǵı kitaplar</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Kitap atı</th>
                        <th>Baxası</th>
                        <th>Sanı</th>
                        <th>Jámi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($books as $item): ?>
                        <?php $sum = $item['price'] * $item['quantity']; $total += $sum; ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td><?= number_format($item['price'], 2) ?> swm</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($sum, 2) ?> swm</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Jámi:</th>
                        <th><?= number_format($total, 2) ?> swm</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

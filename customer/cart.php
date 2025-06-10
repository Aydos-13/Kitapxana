<?php
require_once '../includes/db.php';
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT c.id, b.title, c.quantity, b.price 
    FROM cart_items c 
    JOIN books b ON c.book_id = b.id 
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['quantity'] * $item['price'];
}
include '../includes/header.php';
?>
<div class="container mt-5 p-5">
    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h2 class="mb-4 text-center fw-bold text-primary">🛒 Sebetińiz</h2>
        <?php if (count($cart_items) === 0): ?>
            <div class="alert alert-warning text-center rounded-3">Sebet házirshe bos 😕</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-borderless align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Kitob nomi</th>
                            <th class="text-center">Sanı</th>
                            <th class="text-end">Baxası (dana)</th>
                            <th class="text-end">Jámi</th>
                            <th class="text-center">Háreket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr class="align-middle">
                                <td><?= htmlspecialchars($item['title']) ?></td>
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-end"><?= number_format($item['price'], 2) ?> swm</td>
                                <td class="text-end"><?= number_format($item['quantity'] * $item['price'], 2) ?> swm</td>
                                <td class="text-center">
                                    <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('Bul kitaptı sebetten alıp taslawdı qaleysiz be?');">
                                        <input type="hidden" name="cart_item_id" value="<?= $item['id'] ?>">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            🗑 Alıp taslaw
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="3" class="text-end fw-bold">Ulıwmalıq summa:</td>
                            <td class="text-end fw-bold text-success"><?= number_format($total_price, 2) ?> swm</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="checkout.php" class="btn btn-lg btn-primary px-5 rounded-pill shadow">
                    ✅ Buyurtpa beriw
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

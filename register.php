<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $role     = 'customer';

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$name, $email, $password, $phone, $address, $role]);
        $_SESSION['success'] = "Dizimnen ótiw tabıslı juwmaqlandı. Sistemaǵa kiriń.";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $error = "Bul email menen paydalanıwshı álleqashan dizimnen ótken.";
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <h2 class="mb-4 text-center text-primary">Dizimnen ótiw</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Atıńız</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parol</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefon nomerińiz</label>
                    <input type="text" name="phone" class="form-control rounded-3">
                </div>
                <div class="mb-3">
                    <label class="form-label">Manzilińiz</label>
                    <textarea name="address" class="form-control rounded-3" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2">Dizimnen ótiw</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
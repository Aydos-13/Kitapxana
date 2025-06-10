<?php
require_once 'includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php"); break;
            default:
                header("Location: index.php");
        }
        exit();
    } else {
        $error = "Email yamasa parol qate!";
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4 p-4">
            <h2 class="mb-4 text-center text-success">Sistemaǵa kiriw</h2>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Parol</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                </div>
                <button type="submit" class="btn btn-success w-100 rounded-3 py-2">Kiriw</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

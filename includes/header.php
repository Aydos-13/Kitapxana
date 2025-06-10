<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Онлайн Магазин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 56px;
            background-color:rgb(6, 98, 129);
            padding: 20px;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            margin-top: 56px;
        }

        .navbar-brand {
            font-weight: bold;
            color: #ffffff;
        }

        .nav-link:hover {
            text-decoration: underline;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow fixed-top" style="background-color:rgb(25, 100, 135);">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">📚 Kitaplar dúkanı</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['role'] === 'customer'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/customer/my_orders.php"><i class="bi bi-bag"></i> Meniń buyurtpalarım</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/customer/cart.php"><i class="bi bi-cart3"></i> Sebet</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $_SESSION['user']['role'] === 'admin' ? '/admin/dashboard.php' : '/index.php' ?>">
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                                (<?= $_SESSION['user']['role'] === 'admin' ? 'Admin' : 'Qariydar' ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout.php"><i class="bi bi-box-arrow-right"></i> Shıǵıw</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/login.php"><i class="bi bi-box-arrow-in-right"></i> Kiriw</a></li>
                        <li class="nav-item"><a class="nav-link" href="/register.php"><i class="bi bi-pencil-square"></i> Dizimnen ótiw</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
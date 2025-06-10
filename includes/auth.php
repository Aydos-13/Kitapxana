<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}


function isCustomer() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'customer';
}
?>
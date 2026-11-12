<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
require_once __DIR__ . '/admin_roles.php';

if (!isset($_SESSION['user_id']) || !isAdminAreaRole($_SESSION['role'] ?? null)) {
    header('Location: ' . applicationBaseUrl() . '/admin/login.php');
    exit;
}

if (($_SESSION['role'] ?? null) === 'admin') {
    $_SESSION['role'] = 'manager';
}

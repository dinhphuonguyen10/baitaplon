<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION = [];
session_destroy();

require_once __DIR__ . '/../include/admin_roles.php';
header('Location: ' . applicationBaseUrl() . '/admin/login.php');
exit;

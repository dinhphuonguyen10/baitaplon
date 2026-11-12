<?php
require_once __DIR__ . '/../../include/admin_guard.php';
requireManagerRole();

header('Location: ' . adminRoleHomeUrl($_SESSION['role']));
exit;

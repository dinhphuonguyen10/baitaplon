<?php
require_once __DIR__ . '/../../include/admin_guard.php';
requireStaffRole();
header('Location: ' . applicationBaseUrl() . '/admin/index.php');
exit;

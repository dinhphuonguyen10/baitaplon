<?php
function isAdminAreaRole(?string $role): bool
{
    return in_array($role, ['admin', 'manager', 'staff'], true);
}

function isManagerRole(?string $role): bool
{
    return in_array($role, ['admin', 'manager'], true);
}

function isStaffRole(?string $role): bool
{
    return $role === 'staff';
}

function requireStaffRole(): void
{
    if (!isStaffRole($_SESSION['role'] ?? null)) {
        header('Location: ' . adminRoleHomeUrl($_SESSION['role'] ?? ''));
        exit;
    }
}

function requireManagerRole(): void
{
    if (!isManagerRole($_SESSION['role'] ?? null)) {
        header('Location: ' . adminRoleHomeUrl($_SESSION['role'] ?? ''));
        exit;
    }
}

function applicationBaseUrl(): string
{
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $appBaseUrl = preg_replace('~/(?:admin|api)(?:/.*)?$~', '', $scriptDirectory);

    return rtrim($appBaseUrl, '/');
}

function adminRoleHomeUrl(string $role): string
{
    $dashboard = isAdminAreaRole($role)
        ? '/admin/index.php'
        : '/admin/login.php';

    return applicationBaseUrl() . $dashboard;
}

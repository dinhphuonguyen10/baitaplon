<?php
// Kiểm tra trạng thái đăng nhập của người dùng
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "success",
        "username" => $_SESSION['username'],
        "role" => $_SESSION['role'],
        "user_id" => $_SESSION['user_id']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
}
?>

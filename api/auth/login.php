<?php
// api/auth/login.php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/db.php';

$json = file_get_contents('php://input');
$data = json_decode($json);

if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!'], JSON_UNESCAPED_UNICODE);
    exit;
}

$username = trim($data->username);
$password = $data->password;

// Tìm tài khoản theo username (cho phép cả customer, staff, manager, admin đăng nhập)
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $isMatch = password_verify($password, $user['password']) || $password === $user['password'];

    if ($isMatch) {
        // Tự động nâng cấp mật khẩu sang hash chuẩn nếu trong DB đang lưu dạng plain-text
        if ($password === $user['password']) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($upStmt) {
                $upStmt->bind_param('si', $newHash, $user['id']);
                $upStmt->execute();
                $upStmt->close();
            }
        }

        session_regenerate_id(true);
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        http_response_code(200);
        echo json_encode([
            'status'   => 'success',
            'message'  => 'Đăng nhập thành công!',
            'role'     => $user['role'],
            'username' => $user['username'],
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng.'], JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();

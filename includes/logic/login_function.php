<?php
session_start();
require_once __DIR__ . '/../db/database.php';
global $conn;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ tài khoản và mật khẩu";
    header("Location: ../pages/user/login.php");
    exit;
}

// Lấy thêm cột role
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    // Kiểm tra role để chuyển hướng
    if ($user['role'] === 'admin') {
        header("Location: ../../pages/admin/dashboard.php");
    } else {
        header("Location: ../../pages/user/home.php");
    }
    exit;
} else {
    $_SESSION['error'] = "Tài khoản hoặc mật khẩu không đúng";
    header("Location: ../../pages/user/login.php");
    exit;
}
?>

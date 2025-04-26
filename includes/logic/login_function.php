<?php
session_start();
global $conn;
require_once '../db/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
        ];
        header("Location: ../../pages/user/home.php");
        exit;
    } else {
        $_SESSION['error'] = "Sai tên đăng nhập hoặc mật khẩu";
        header("Location: ../../pages/user/login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin";
    header("Location: ../../pages/user/login.php");
    exit;
}

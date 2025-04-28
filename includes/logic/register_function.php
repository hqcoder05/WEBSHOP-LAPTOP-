<?php
session_start();
require_once __DIR__ . '/../db/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$email = $_POST['email'] ?? '';

if ($username && $password && $confirm) {
    if ($password !== $confirm) {
        $_SESSION['error'] = "Mật khẩu không khớp";
        header("Location: ../../pages/user/register.php");
        exit;
    }
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
        $_SESSION['error'] = "Tên đăng nhập đã tồn tại";
        header("Location: ../../pages/user/register.php");
        exit;
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);
    $stmt->execute();
    $_SESSION['user'] = [
        'id' => $stmt->insert_id,
        'username' => $username,
    ];
    $stmt->close();
    header("Location: ../../pages/user/home.php");
    exit;
} else {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";
    header("Location: ../../pages/user/register.php");
    exit;
}
?>
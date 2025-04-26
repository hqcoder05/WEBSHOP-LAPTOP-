<?php
global $conn;
session_start();
require_once '../../includes/db/database.php';

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

    // Kiểm tra username đã tồn tại chưa
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $_SESSION['error'] = "Tên đăng nhập đã tồn tại";
        header("Location: ../../pages/user/register.php");
        exit;
    }

    // Hash mật khẩu và insert
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);
    $stmt->execute();

    $_SESSION['user'] = [
        'id' => $stmt->insert_id,
        'username' => $username,
    ];
    header("Location: ../../pages/user/home.php");
    exit;

} else {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";
    header("Location: ../../pages/user/register.php");
    exit;
}

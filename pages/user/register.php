<?php
// Bao gồm file chứa các hàm người dùng
include_once '../../includes/autoload.php';

// Kiểm tra xem có yêu cầu AJAX không
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password']; // Nhập lại mật khẩu
    $email = $_POST['email'];

    // Kiểm tra xem mật khẩu và nhập lại mật khẩu có khớp không
    if ($password !== $confirmPassword) {
        echo json_encode(['message' => 'Mật khẩu không khớp!']);
        exit;
    }

    // Gọi hàm registerUser   để đăng ký người dùng
    $result = registerUser  ($username, $password, $email);

    // Trả về kết quả dưới dạng JSON
    echo json_encode(['message' => $result]);
    exit; // Dừng thực thi mã sau khi trả về kết quả
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <script src="../../assets/js/jquery-3.7.1.js"></script>
    <script src="../../assets/js/user_register.js"></script>
</head>
<body>
<h2>Đăng Ký Tài Khoản</h2>
<form id="registerForm">
    <label for="username">Tên Đăng Nhập:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Mật Khẩu:</label>
    <input type="password" id="password" name="password" required><br><br>

    <label for="confirm_password">Nhập Lại Mật Khẩu:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <input type="submit" value="Đăng Ký">
</form>

<div id="message"></div>
</body>
</html>
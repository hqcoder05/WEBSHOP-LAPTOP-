<?php
class LoginController {
    public function index() {
        // Kiểm tra nếu người dùng đã đăng nhập
        session_start();
        if (isset($_SESSION['user'])) {
            header('Location: ../views/user/home.php'); // Redirect về trang chủ nếu đã đăng nhập
            exit;
        }

        // Nếu người dùng gửi dữ liệu form login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new User();
            $result = $userModel->login($username, $password);

            if ($result['success']) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user'] = $result['user'];
                header('Location: ../views/user/home.php'); // Redirect về trang chủ
                exit;
            } else {
                $error = $result['message']; // Thông báo lỗi nếu đăng nhập thất bại
                include 'views/user/login.php'; // Quay lại trang đăng nhập với lỗi
                return;
            }
        }

        include 'views/user/login.php'; // Hiển thị form đăng nhập
    }
}

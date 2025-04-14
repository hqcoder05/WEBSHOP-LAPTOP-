<?php
class RegisterController {
    public function index() {
        // Nếu người dùng gửi dữ liệu form đăng ký
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $result = $userModel->register($username, $email, $password);

            if ($result['success']) {
                // Redirect về trang login sau khi đăng ký thành công
                header('Location: ../views/user/login.php');
                exit;
            } else {
                $error = $result['message']; // Thông báo lỗi nếu đăng ký thất bại
                include 'views/user/register.php'; // Quay lại trang đăng ký với lỗi
                return;
            }
        }

        include 'views/user/register.php'; // Hiển thị form đăng ký
    }
}

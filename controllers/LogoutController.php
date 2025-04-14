<?php
class LogoutController {
    public function index() {
        // Xóa session người dùng và đăng xuất
        session_start();
        session_unset();
        session_destroy();

        // Redirect về trang chủ sau khi đăng xuất
        header('Location: ../views/user/home.php');
        exit;
    }
}

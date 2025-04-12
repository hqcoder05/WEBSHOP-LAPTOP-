<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

use models\User;

class LoginController
{
    public function handle()
    {
        session_start();

        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (isset($_SESSION['user'])) {
            header("Location: index.php?page=home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
                include __DIR__ . '/../views/user/login.php';
                return;
            }

            $db = new Database();
            $conn = $db->getConnection();

            $user = new User($conn);
            $user->username = $username;
            $user->password = $password;

            $userData = $user->login();

            if ($userData) {
                $_SESSION['user'] = $userData;
                header("Location: index.php?page=home");
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
                include __DIR__ . '/../views/user/login.php';
                return;
            }
        }

        // Hiển thị form đăng nhập nếu chưa gửi form
        include __DIR__ . '/../views/user/login.php';
    }
}

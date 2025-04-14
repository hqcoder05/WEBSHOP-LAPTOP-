<?php
class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Đăng ký tài khoản mới
    public function register($username, $password, $email) {
        // Kiểm tra username đã tồn tại chưa
        $checkUsername = $this->conn->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
        $checkUsername->execute(['username' => $username]);
        if ($checkUsername->fetch()) {
            return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại'];
        }

        // Kiểm tra email đã tồn tại chưa
        $checkEmail = $this->conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $checkEmail->execute(['email' => $email]);
        if ($checkEmail->fetch()) {
            return ['success' => false, 'message' => 'Email đã được sử dụng'];
        }

        // Thêm người dùng mới
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'email' => $email
        ]);

        if ($result) {
            return ['success' => true, 'message' => 'Đăng ký thành công'];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi đăng ký'];
        }
    }

    // Đăng nhập
    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Sai email hoặc mật khẩu'];
    }
}

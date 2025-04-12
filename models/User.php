<?php
class User {
    private $conn;
    private $table = "users"; // Tên bảng trong database

    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    //  Hàm kiểm tra đăng nhập
    public function login() {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Kiểm tra mật khẩu
            if (password_verify($this->password, $user['password'])) {
                return $user; // Trả về thông tin user
            }
        }
        return false; // Sai username hoặc password
    }

    public function register() {
        // Kiểm tra username đã tồn tại chưa
        $checkQuery = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":username", $this->username);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            return "Username đã tồn tại!";
        }

        // Mã hóa mật khẩu
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Thêm user mới vào database
        $query = "INSERT INTO " . $this->table . " (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":email", $this->email);

        if ($stmt->execute()) {
            return true; // Đăng ký thành công
        }
        return false; // Đăng ký thất bại
    }
}
?>

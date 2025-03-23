<?php
class Database {
    private $host = "localhost"; // Máy chủ cơ sở dữ liệu
    private $db_name = "webshop"; // Tên cơ sở dữ liệu
    private $username = "root"; // Tên người dùng cơ sở dữ liệu
    private $password = ""; // Mật khẩu cơ sở dữ liệu
    public $conn; // Kết nối cơ sở dữ liệu

    // Lấy kết nối cơ sở dữ liệu
    public function getConnection() {
        $this->conn = null;
        try {
            // Tạo kết nối PDO mới
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8"); // Đặt mã hóa ký tự là UTF-8
        } catch (PDOException $exception) {
            // Xử lý lỗi kết nối
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn; // Trả về kết nối
    }
}
?>
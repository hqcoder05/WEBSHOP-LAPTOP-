<?php
<<<<<<< HEAD
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Database
{
    private $host = "localhost";

    private $dbname = "webshop_laptop";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<h2 style='color: green;'>✅ Kết nối thành công!</h2>";
        } catch (PDOException $e) {
            echo "<h2 style='color: red;'>❌ Kết nối thất bại!</h2>" . $e->getMessage();
        }
        return $this->conn;
    }

}
$db = new Database();
$conn = $db->connect();
?>
=======
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
>>>>>>> c3b1610133799bc16ffad304466e9a37eba96176

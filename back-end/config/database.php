<?php
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

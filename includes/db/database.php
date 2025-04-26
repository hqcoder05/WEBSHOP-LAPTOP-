<?php
$host = 'localhost';
$user = 'root';
$pass = 'mysql123';
$dbname = 'laptop_shop';
global $conn;

$conn = new mysqli($host, $user, $pass, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Đặt charset cho kết nối
$conn->set_charset("utf8mb4");

// Hàm đóng kết nối khi không sử dụng nữa
function closeConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
?>

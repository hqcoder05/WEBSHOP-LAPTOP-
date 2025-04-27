<?php
$host = 'localhost';
$user = 'root';
$pass = 'mysql123';
$dbname = 'laptop_shop';

global $conn;
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

function closeConnection() {
    global $conn;
    if ($conn) {
        $conn->close();
    }
}
?>
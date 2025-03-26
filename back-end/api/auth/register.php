<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../../back-end/config/database.php';
require_once '../../../back-end/models/User.php';

// Kết nối database
$database = new Database();
$db = $database->connect();

// Kiểm tra dữ liệu gửi lên
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password) && !empty($data->email)) {
    $user = new User($db);
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;

    $registerResult = $user->register();

    if ($registerResult === true) {
        echo json_encode(["message" => "Đăng ký thành công!"]);
    } elseif ($registerResult === "Username đã tồn tại!") {
        echo json_encode(["message" => "Username đã tồn tại!"]);
    } else {
        echo json_encode(["message" => "Đăng ký thất bại!"]);
    }
} else {
    echo json_encode(["message" => "Vui lòng nhập đầy đủ username, password và email!"]);
}
?>

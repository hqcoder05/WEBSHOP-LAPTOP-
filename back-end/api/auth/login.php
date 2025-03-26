<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../../back-end/config/database.php';
require_once '../../../back-end/models/User.php';

// Kết nối Database
$database = new Database();
$db = $database->connect();
$user = new User($db);

// Nhận dữ liệu từ request JSON
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $loggedInUser = $user->login($data->email, $data->password);

    if ($loggedInUser) {
        // Tạo token (tạm thời dùng session)
        session_start();
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['username'] = $loggedInUser['username'];

        echo json_encode([
            "status" => "success",
            "message" => "Đăng nhập thành công!",
            "user" => [
                "id" => $loggedInUser['id'],
                "username" => $loggedInUser['username'],
                "email" => $loggedInUser['email']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Sai email hoặc mật khẩu!"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Vui lòng nhập email và mật khẩu!"]);
}


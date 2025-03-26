<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    if (isset($_SESSION['user_id'])) {
        session_unset();
        session_destroy();
        session_regenerate_id(true);

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Đăng xuất thành công!"]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Bạn chưa đăng nhập!"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Đã xảy ra lỗi: " . $e->getMessage()]);
}
?>

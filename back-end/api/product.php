<?php
require_once '../config/database.php';
require_once '../models/Product.php';

header("Content-Type: application/json; charset=UTF-8");

// Lấy phương thức HTTP từ yêu cầu máy chủ
$method = $_SERVER['REQUEST_METHOD'];

// Tạo một đối tượng Product mới
$product = new Product();

switch ($method) {
    // Xử lý yêu cầu GET để lấy dữ liệu sản phẩm
    case 'GET':
        if (isset($_GET['id'])) {
            // Nếu có ID, lấy sản phẩm cụ thể
            $id = intval($_GET['id']);
            $result = $product->readOne($id);
        } else {
            // Ngược lại, lấy tất cả các sản phẩm
            $result = $product->readAll();
        }
        echo json_encode($result);
        break;

    // Xử lý yêu cầu POST để tạo sản phẩm mới
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $result = $product->create($data);
        echo json_encode($result);
        break;

    // Xử lý yêu cầu PUT để cập nhật sản phẩm hiện có
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $result = $product->update($data);
        echo json_encode($result);
        break;

    // Xử lý yêu cầu DELETE để xóa sản phẩm
    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $product->delete($id);
            echo json_encode($result);
        }
        break;

    // Phản hồi mặc định cho các phương thức không được hỗ trợ
    default:
        echo json_encode(array("message" => "Method not supported"));
        break;
}
?>
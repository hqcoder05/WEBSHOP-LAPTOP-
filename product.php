<?php
require_once '../config/database.php';
require_once '../models/Product.php';

header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

$product = new Product();

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $product->readOne($id);
        } else {
            $result = $product->readAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $result = $product->create($data);
        echo json_encode($result);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $result = $product->update($data);
        echo json_encode($result);
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $result = $product->delete($id);
            echo json_encode($result);
        }
        break;

    default:
        echo json_encode(array("message" => "Method not supported"));
        break;
}
?>
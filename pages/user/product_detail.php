<?php

require_once 'product.php';

class ProductAPI {
    private $products = [];

    // Thêm sản phẩm mới
    public function createProduct($data) {
        $newProduct = new Product(
            count($this->products) + 1,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['quantity']
        );
        $this->products[] = $newProduct;
        return $newProduct;
    }

    // Lấy danh sách sản phẩm
    public function getProducts() {
        return $this->products;
    }

    // Lấy chi tiết sản phẩm
    public function getProductById($id) {
        foreach ($this->products as $product) {
            if ($product->getId() == $id) {
                return $product;
            }
        }
        return null;
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $data) {
        foreach ($this->products as $product) {
            if ($product->getId() == $id) {
                $product->setName($data['name']);
                $product->setDescription($data['description']);
                $product->setPrice($data['price']);
                $product->setQuantity($data['quantity']);
                return $product;
            }
        }
        return null;
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        foreach ($this->products as $key => $product) {
            if ($product->getId() == $id) {
                unset($this->products[$key]);
                return true;
            }
        }
        return false;
    }
}

?>

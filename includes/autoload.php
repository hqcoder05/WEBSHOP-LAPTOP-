<?php
// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Đường dẫn cơ bản
define('BASE_PATH', __DIR__);

// Nạp file kết nối CSDL
require_once BASE_PATH . '/db/database.php';


// Nạp các file hàm xử lý logic
require_once BASE_PATH . '/logic/user_functions.php';

require_once BASE_PATH . '/logic/product_functions.php';

require_once BASE_PATH . '/logic/cart_functions.php';

require_once BASE_PATH . '/logic/category_functions.php';

require_once BASE_PATH . '/logic/order_functions.php';

// Nạp helper
require_once BASE_PATH . '/helpers/utils.php';
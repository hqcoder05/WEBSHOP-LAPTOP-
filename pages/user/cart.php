<?php
session_start();

// Giả lập lấy dữ liệu từ database thông qua API
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => [
            'id' => 1,
            'name' => 'Asus Vivobook Go 15 E1504FA R5 7520U (NJ776W)',
            'price' => 12990000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        2 => [
            'id' => 2,
            'name' => 'HP 15 fc0085AU R5 7430U (A6VV8PA)',
            'price' => 13490000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        3 => [
            'id' => 3,
            'name' => 'Dell Inspiron 15 3520 i5 1235U (N5I5057W1)',
            'price' => 16490000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        4 => [
            'id' => 4,
            'name' => 'Acer Aspire 7 A715 76 53PJ i5 12450H (NH.QGESV.007)',
            'price' => 13790000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        5 => [
            'id' => 5,
            'name' => 'MacBook Air 13 inch M4 16GB/256GB',
            'price' => 26990000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        6 => [
            'id' => 6,
            'name' => 'HP 15 fd0234TU i5 1334U (9Q969PA)',
            'price' => 15890000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        7 => [
            'id' => 7,
            'name' => 'Asus Vivobook 15 OLED A1505ZA i5 12500H (MA415W)',
            'price' => 16690000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        8 => [
            'id' => 8,
            'name' => 'MacBook Air 13 inch M1 8GB/256GB',
            'price' => 17090000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        9 => [
            'id' => 9,
            'name' => 'Acer Aspire 3 A314 42P R3B3 R7 5700U (NX.KSFSV.001)',
            'price' => 12590000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ],
        10 => [
            'id' => 10,
            'name' => 'Lenovo IdeaPad Slim 3 15IRH10 i5 13420H (83K1000HVN)',
            'price' => 16490000,
            'quantity' => 1,
            'image' => 'images/asus-vivobook-go-15.jpg'
        ]
    ];
}

// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        $quantity = intval($quantity);
        if ($quantity > 0) {
            // Cập nhật số lượng
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            // Xóa sản phẩm khỏi giỏ hàng
            unset($_SESSION['cart'][$product_id]);
        }
    }
    
    // Chuyển hướng để tránh form resubmission
    header('Location: cart.php');
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        header('Location: cart.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Online - Giỏ hàng</title>
    <link rel="stylesheet" href="../css/test.css">
    <script src="../scripts/cart.js"></script>
  </head>
  <body>
    <div class="contain-to-grid">
      <nav class="top-bar" data-topbar>
        <ul class="title-area">
          <li class="name">
            <h1><a href="/dashboard/index.html">Shop Online</a></h1>
          </li>
        </ul>
        <section class="top-bar-section">
          <!-- Right Nav Section -->
          <ul class="right">
            <li class="">
              <a href="/applications.php">Sản phẩm</a>
            </li>
            <li class="active"><a href="/cart.php">Giỏ hàng <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span></a></li>
            <li class=""><a href="/about.php">Giới thiệu</a></li>
            <li class=""><a href="/contact.php">Liên hệ</a></li>
          </ul>
        </section>
      </nav>
    </div>
    <div id="wrapper">
      <div class="hero">
        <div class="row">
          <div class="large-12 columns">
            <h2>Giỏ hàng của bạn</h2>
          </div>
        </div>
      </div>
      
      <div id="lowerContainer" class="row">
        <div id="content" class="large-12 columns">
          <!-- Giỏ hàng -->
          <?php if (empty($_SESSION['cart'])) { ?>
            <div class="empty-cart">
              <p>Giỏ hàng của bạn đang trống.</p>
              <a href="applications.php" class="continue-shopping">Tiếp tục mua sắm</a>
            </div>
          <?php } else { ?>
            <form method="post" action="cart.php" id="cartForm">
              <table class="cart-table">
                <thead>
                  <tr>
                    <th class="checkbox-column">
                      <div class="select-all-container">
                        <label class="select-all-label">
                          <input type="checkbox" id="selectAll" class="select-all-checkbox">  
                        </label>
                      </div>
                    </th>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $total = 0;
                  foreach ($_SESSION['cart'] as $product_id => $item) { 
                    $subtotal = $item['price'] * $item['quantity'];
                  ?>
                  <tr class="product-row" data-id="<?php echo $product_id; ?>">
                    <td class="checkbox-column">
                      <input type="checkbox" class="product-checkbox" name="selected_products[]" value="<?php echo $product_id; ?>">
                    </td>
                    <td class="product-name-cell">
                      <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    </td>
                    <td class="product-image">
                      <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="product-img">
                    </td>
                    <td class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                    <td>
                      <input type="number" name="quantity[<?php echo $product_id; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" data-id="<?php echo $product_id; ?>">
                    </td>
                    <td class="product-subtotal" data-value="<?php echo $subtotal; ?>"><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</td>
                    <td>
                      <a href="#" class="remove-btn" data-id="<?php echo $product_id; ?>">Xóa</a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
                    <td id="cartTotal" colspan="2"><strong>0 VNĐ</strong></td>
                  </tr>
                </tfoot>
              </table>
              
              <div class="cart-actions">
                <button type="button" id="selectAllBtn" class="select-all-btn">Chọn tất cả</button>
                <button type="button" id="checkoutSelected" class="checkout-btn disabled" disabled>Thanh toán đơn hàng</button>
              </div>
            </form>
          <?php } ?>
        </div>
      </div>
    </div>
    
    <?php include './footer.php'; ?>
    
  </body>
</html>
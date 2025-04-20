<?php
session_start();

// Kiểm tra nếu không có sản phẩm nào được chọn để thanh toán
if (!isset($_POST['selected_products']) && !isset($_SESSION['checkout_products'])) {
    // Lưu thông báo lỗi vào session
    $_SESSION['checkout_error'] = 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.';
    // Chuyển hướng về trang giỏ hàng
    header('Location: cart.php');
    exit;
}

// Lưu sản phẩm được chọn vào session để sử dụng sau khi gửi form
if (isset($_POST['selected_products'])) {
    $_SESSION['checkout_products'] = $_POST['selected_products'];
}

// Lấy danh sách sản phẩm đã chọn
$selected_products = $_SESSION['checkout_products'];

// Tính tổng tiền và tạo mảng chứa thông tin sản phẩm đã chọn
$checkout_items = [];
$subtotal = 0;

foreach ($selected_products as $product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        $item = $_SESSION['cart'][$product_id];
        $item_total = $item['price'] * $item['quantity'];
        $subtotal += $item_total;
        $checkout_items[$product_id] = $item;
    }
}

// Tính các chi phí khác
$shipping_fee = 30000; // Phí vận chuyển
$tax_rate = 0.08; // Thuế 8%
$tax = $subtotal * $tax_rate;

// Xử lý voucher
$voucher_discount = 0;
$voucher_code = '';
$voucher_error = '';
$voucher_success = '';

// Danh sách voucher có sẵn
$available_vouchers = [
    'VOUCHER20K' => 20000,
    'GIAM50K' => 50000,
    'KHUYENMAI100K' => 100000,
    'FREESHIP' => $shipping_fee
];

// Xử lý khi nút áp dụng voucher được nhấn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_voucher'])) {
    $voucher_code = trim($_POST['voucher_code']);
    
    if (empty($voucher_code)) {
        $voucher_error = 'Vui lòng nhập mã voucher';
    } else {
        $voucher_code = strtoupper($voucher_code);
        
        if (isset($available_vouchers[$voucher_code])) {
            $voucher_discount = $available_vouchers[$voucher_code];
            $_SESSION['voucher'] = [
                'code' => $voucher_code,
                'discount' => $voucher_discount
            ];
            $voucher_success = 'Áp dụng mã giảm giá thành công!';
        } else {
            $voucher_error = 'Mã voucher không hợp lệ hoặc đã hết hạn';
        }
    }
} elseif (isset($_SESSION['voucher'])) {
    // Lấy voucher từ session nếu đã được áp dụng trước đó
    $voucher_code = $_SESSION['voucher']['code'];
    $voucher_discount = $_SESSION['voucher']['discount'];
    $voucher_success = 'Mã giảm giá đã được áp dụng!';
}

// Xử lý khi nút xóa voucher được nhấn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_voucher'])) {
    unset($_SESSION['voucher']);
    $voucher_discount = 0;
    $voucher_code = '';
}

// Tính tổng cuối cùng
$total = $subtotal + $shipping_fee + $tax - $voucher_discount;
if ($total < 0) $total = 0; // Đảm bảo tổng không âm

// Xử lý khi form thanh toán được gửi
$payment_success = false;
$order_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    // Kiểm tra dữ liệu đầu vào
    $required_fields = [
        'fullname', 'email', 'phone', 'address', 'city', 'payment_method'
    ];
    
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Vui lòng nhập ' . $field;
        }
    }
    
    // Nếu không có lỗi, xử lý thanh toán
    if (empty($errors)) {
        // Trong thực tế, đây là nơi bạn gọi API thanh toán hoặc lưu đơn hàng vào database
        // Ở đây, chúng ta chỉ giả lập thành công
        
        // Tạo mã đơn hàng
        $order_number = 'ORD-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        
        // Xóa sản phẩm đã thanh toán khỏi giỏ hàng
        foreach ($selected_products as $product_id) {
            unset($_SESSION['cart'][$product_id]);
        }
        
        // Đánh dấu thanh toán thành công
        $payment_success = true;
        
        // Xóa sản phẩm đã chọn và voucher khỏi session
        unset($_SESSION['checkout_products']);
        unset($_SESSION['voucher']);
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Online - Thanh toán</title>
    <link rel="stylesheet" href="../css/test.css">
    <link rel="stylesheet" href="../css/checkout.css">
    <script src="../scripts/checkout.js"></script>
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
            <li class=""><a href="/cart.php">Giỏ hàng <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span></a></li>
            <li class=""><a href="/about.php">Giới thiệu</a></li>
            <li class=""><a href="/contact.php">Liên hệ</a></li>
          </ul>
        </section>
      </nav>
    </div>
    <div id="wrapper">
      <!-- Hiển thị kết quả thanh toán nếu thành công -->
      <?php if ($payment_success): ?>
      <div class="payment-success">
        <div class="success-icon">✓</div>
        <h2>Thanh toán thành công!</h2>
        <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được xác nhận.</p>
        <div class="order-details">
          <p><strong>Mã đơn hàng:</strong> <?php echo $order_number; ?></p>
          <p><strong>Tổng thanh toán:</strong> <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
          <p>Chúng tôi sẽ gửi email xác nhận đơn hàng và thông tin vận chuyển đến bạn trong thời gian sớm nhất.</p>
        </div>
        <div class="action-buttons">
          <a href="applications.php" class="continue-shopping">Tiếp tục mua sắm</a>
        </div>
      </div>
      <?php else: ?>
      <div class="hero">
        <div class="row">
          <div class="large-12 columns">
            <h2>Thanh toán đơn hàng</h2>
            <p>Vui lòng nhập thông tin thanh toán dưới đây</p>
          </div>
        </div>
      </div>
      
      <div id="checkout-container" class="row">
        <div class="checkout-form-container">
          <!-- Form thanh toán -->
          <form method="post" action="checkout.php" id="checkoutForm">
            <div class="checkout-sections">
              <!-- Phần thông tin người nhận -->
              <div class="checkout-section">
                <h3>Thông tin người nhận</h3>
                <div class="form-row">
                  <div class="form-group">
                    <label for="fullname">Họ và tên <span class="required">*</span></label>
                    <input type="text" id="fullname" name="fullname" required>
                    <?php if (isset($errors['fullname'])): ?>
                      <div class="error-message"><?php echo $errors['fullname']; ?></div>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                    <?php if (isset($errors['email'])): ?>
                      <div class="error-message"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="phone">Số điện thoại <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" required>
                    <?php if (isset($errors['phone'])): ?>
                      <div class="error-message"><?php echo $errors['phone']; ?></div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              
              <!-- Phần địa chỉ giao hàng -->
              <div class="checkout-section">
                <h3>Địa chỉ giao hàng</h3>
                <div class="form-row">
                  <div class="form-group">
                    <label for="address">Địa chỉ <span class="required">*</span></label>
                    <input type="text" id="address" name="address" required>
                    <?php if (isset($errors['address'])): ?>
                      <div class="error-message"><?php echo $errors['address']; ?></div>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group half">
                    <label for="city">Thành phố <span class="required">*</span></label>
                    <input type="text" id="city" name="city" required>
                    <?php if (isset($errors['city'])): ?>
                      <div class="error-message"><?php echo $errors['city']; ?></div>
                    <?php endif; ?>
                  </div>
                  
                  <div class="form-group half">
                    <label for="district">Quận/Huyện <span class="required">*</span></label>
                    <input type="text" id="district" name="district" required>
                    <?php if (isset($errors['district'])): ?>
                      <div class="error-message"><?php echo $errors['district']; ?></div>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="form-row">
                  <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                  </div>
                </div>
              </div>
              
              <!-- Phần phương thức thanh toán -->
              <div class="checkout-section">
                <h3>Phương thức thanh toán</h3>
                <div class="form-row">
                  <div class="payment-methods">
                    <div class="payment-method">
                      <input type="radio" id="cod" name="payment_method" value="cod" checked>
                      <label for="cod">Thanh toán khi nhận hàng (COD)</label>
                    </div>
                    
                    <div class="payment-method">
                      <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                      <label for="bank_transfer">Chuyển khoản ngân hàng</label>
                    </div>
                    
                    <div class="payment-method">
                      <input type="radio" id="momo" name="payment_method" value="momo">
                      <label for="momo">Ví MoMo</label>
                    </div>
                    
                    <div class="payment-method">
                      <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                      <label for="credit_card">Thẻ tín dụng/Ghi nợ</label>
                    </div>
                  </div>
                  <?php if (isset($errors['payment_method'])): ?>
                    <div class="error-message"><?php echo $errors['payment_method']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <!-- Nút thanh toán -->
            <div class="form-actions">
              <input type="hidden" name="process_payment" value="1">
              <button type="submit" class="payment-button">Hoàn tất thanh toán</button>
              <a href="cart.php" class="back-to-cart">Quay lại giỏ hàng</a>
            </div>
          </form>
        </div>
        
        <!-- Phần tổng quan đơn hàng -->
        <div class="order-summary">
          <h3>Thông tin đơn hàng</h3>
          <div class="order-products">
            <?php foreach ($checkout_items as $product_id => $item): ?>
            <div class="order-product">
              <div class="product-image">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="placeholder-image" style="display:none;"><span>Laptop</span></div>
              </div>
              <div class="product-details">
                <div class="product-name"><?php echo $item['name']; ?></div>
                <div class="product-price-qty">
                  <span class="product-price"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</span>
                  <span class="product-qty">x <?php echo $item['quantity']; ?></span>
                </div>
                <div class="product-subtotal"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VNĐ</div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          
          <!-- Phần nhập mã voucher -->
          <div class="voucher-section">
            <h4>Mã giảm giá</h4>
            <form method="post" action="checkout.php" class="voucher-form">
              <div class="voucher-input-group">
                <input type="text" name="voucher_code" placeholder="Nhập mã giảm giá" value="<?php echo $voucher_code; ?>">
                <?php if (empty($voucher_code)): ?>
                  <button type="submit" name="apply_voucher" class="apply-voucher-btn">Áp dụng</button>
                <?php else: ?>
                  <button type="submit" name="remove_voucher" class="remove-voucher-btn">Xóa</button>
                <?php endif; ?>
              </div>
              
              <?php if (!empty($voucher_error)): ?>
                <div class="voucher-error"><?php echo $voucher_error; ?></div>
              <?php endif; ?>
              
              <?php if (!empty($voucher_success)): ?>
                <div class="voucher-success"><?php echo $voucher_success; ?></div>
              <?php endif; ?>
              
              <div class="voucher-tips">
                <p>Gợi ý: Thử mã <strong>VOUCHER20K</strong>, <strong>GIAM50K</strong>, <strong>KHUYENMAI100K</strong> hoặc <strong>FREESHIP</strong></p>
              </div>
            </form>
          </div>
          
          <div class="order-totals">
            <div class="total-row">
              <span>Tạm tính:</span>
              <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</span>
            </div>
            <div class="total-row">
              <span>Phí vận chuyển:</span>
              <span><?php echo number_format($shipping_fee, 0, ',', '.'); ?> VNĐ</span>
            </div>
            <div class="total-row">
              <span>Thuế (8%):</span>
              <span><?php echo number_format($tax, 0, ',', '.'); ?> VNĐ</span>
            </div>
            
            <?php if ($voucher_discount > 0): ?>
            <div class="total-row voucher-discount">
              <span>Giảm giá (<?php echo $voucher_code; ?>):</span>
              <span>-<?php echo number_format($voucher_discount, 0, ',', '.'); ?> VNĐ</span>
            </div>
            <?php endif; ?>
            
            <div class="total-row grand-total">
              <span>Tổng cộng:</span>
              <span><?php echo number_format($total, 0, ',', '.'); ?> VNĐ</span>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
              <?php include 'footer.php'; ?>
    
    
  </body>
</html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/Laptop_Shop - Copy/assets/css/header.css">
<script src="/Laptop_Shop - Copy/assets/js/jquery-3.7.1.js"></script>

<header>
    <div class="header-container">
        <!-- Thêm liên kết đến trang chủ -->
        <div class="shop-name">
            <a href="/Laptop_Shop - Copy/pages/user/home.php">Laptop Việt</a>
        </div>
        <nav>
            <ul>
                <li><a href="/Laptop_Shop - Copy/pages/user/home.php">Trang Chủ</a></li>
                <li><a href="/Laptop_Shop - Copy/pages/user/products.php">Sản Phẩm</a></li>
                <li><a href="/Laptop_Shop - Copy/pages/user/orders.php">Tra Cứu Đơn Hàng</a></li>
                <li><a href="/Laptop_Shop - Copy/pages/user/cart.php">Giỏ Hàng</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <form action="/Laptop_Shop - Copy/pages/user/products.php" method="GET">
                <input type="text" name="search" placeholder="Tìm kiếm laptop...">
                <button type="submit">Tìm</button>
            </form>
        </div>
        <div class="login-button">
            <span>Xin chào, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Khách' ?></span>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="/Laptop_Shop - Copy/pages/user/logout.php">Đăng Xuất</a>
            <?php else: ?>
                <a href="/Laptop_Shop - Copy/pages/user/login.php">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </div>
</header>
</body>
</html>
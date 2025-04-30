<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../assets/css/header.css">
<script src="../../assets/js/jquery-3.7.1.js"></script>

<header>
    <div class="header-container">
        <!-- Thêm liên kết đến trang chủ -->
        <div class="shop-name">
            <a href="../user/home.php">Laptop Việt</a>
        </div>
        <nav>
            <ul>
                <li><a href="../user/home.php">Trang Chủ</a></li>
                <li><a href="../user/products.php">Sản Phẩm</a></li>
                <li><a href="../user/orders.php">Tra Cứu Đơn Hàng</a></li>
                <li><a href="../user/cart.php">Giỏ Hàng</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <form action="../user/products.php" method="GET">
                <input type="text" name="search" placeholder="Tìm kiếm laptop...">
                <button type="submit">Tìm</button>
            </form>
        </div>
        <div class="login-button">
            <span>Xin chào, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Khách thân thương' ?></span>
            <?php if (isset($_SESSION['username'])): ?>
                <a href=../user/logout.php>Đăng Xuất</a>
            <?php else: ?>
                <a href="../user/login.php">Đăng Nhập</a>
            <?php endif; ?>
        </div>
    </div>
</header>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopLaptop - Header</title>
    <link rel="stylesheet" href="../../assets/css/header.css"> <!-- Link to external CSS -->
    <script src="../../assets/js/jquery-3.7.1.js"></script>
    <script src="../../assets/js/scripts.js"></script>
</head>
<body>
<header>
    <div class="header-container">
        <div class="shop-name">Laptop Việt</div>
        <nav>
            <ul>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Dòng Laptop</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">DELL</a></li>
                        <li><a href="#">Macbook</a></li>
                        <li><a href="#">Lenovo</a></li>
                        <li><a href="#">HP</a></li>
                    </ul>
                </li>
                <li><a href="../user/orders.php">Tra Cứu Đơn Hàng</a></li>
                <li><a href="../user/cart.php">Giỏ Hàng</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Tìm kiếm laptop...">
                <button type="submit">Tìm</button>
            </form>
        </div>
        <div class="login-button"><a href="../user/login.php">Đăng Nhập</a></div>
    </div>
</header>
</body>
</html>

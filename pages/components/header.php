<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopLaptop - Header</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo img {
            height: 40px; /* Kích thước logo */
        }

        .shop-name {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #3498db;
        }

        .search-bar input {
            padding: 8px;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        .search-bar button {
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: #2980b9;
        }
    </style>
<title>Trang web cửa hàng</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="../../assets/js/user_products.js">
<script type="text/javascript">
  $(document).ready(function($){
    $('#dc_mega-menu-orange').dcMegaMenu({rowItems:'4',speed:'fast',effect:'fade'});
  });
</script>
<style>
/* Tổng thể header */
.header_container {
    width: 100%;
    padding: 0 30px;
    box-sizing: border-box;
}

.header_top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    flex-wrap: wrap;
}

/* Logo */
.logo img {
    max-height: 70px;
}

/* Tìm kiếm */
.search_box {
    flex: 1;
    display: flex;
    justify-content: center;
    margin: 0 20px;
}

.search_box form {
    display: flex;
    width: 100%;
    max-width: 500px;
}

.search_box input[type="text"] {
    padding: 10px;
    flex: 1;
    border: 1px solid #ccc;
    border-right: none;
    border-radius: 4px 0 0 4px;
    font-size: 14px;
}

.search_box input[type="submit"] {
    background-color: #7B3F96;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
    font-weight: bold;
}

.search_box input[type="submit"]:hover {
    background-color: #5e3071;
}

/* Phần bên phải */
.header_right {
    display: flex;
    gap: 15px;
}

.header_right a {
    padding: 8px 12px;
    border: 1px solid #ccc;
    background-color: #f8f8f8;
    text-decoration: none;
    font-weight: bold;
    color: #333;
    border-radius: 4px;
    transition: 0.3s;
}

.header_right a:hover {
    background-color: #e2e2e2;
}

.shopping_cart span {
    color: red;
}

/* Menu */
.menu {
    background-color: #2d2d2d;
    margin-top: 10px;
}

#dc_mega-menu-orange {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

#dc_mega-menu-orange li a {
    display: block;
    padding: 14px 20px;
    color: white;
    text-transform: uppercase;
    font-weight: bold;
    text-decoration: none;
}

#dc_mega-menu-orange li a:hover {
    background-color: #444;
}

</style>
</head>
<body>
<header>
    <div class="header-container">
        <!-- Logo -->
        <div class="logo">
            <img src="header.php" alt="ShopLaptop Logo">
        </div>

        <!-- Tên cửa hàng -->
        <div class="shop-name">
            ShopLaptop
        </div>

        <!-- Thanh điều hướng -->
        <nav>
            <ul>
                <li><a href="../user/home.php">Trang chủ</a></li>
                <li><a href="products.php">Sản phẩm</a></li>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
            </ul>
        </nav>

        <!-- Thanh tìm kiếm -->
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Tìm kiếm laptop...">
                <button type="submit">Tìm</button>
            </form>
        </div>
<div class="header_container">
  <div class="header_top">
    <div class="logo">
      <a href="index.php"><img src="../../assets/images/logo/logo.png" alt="Logo" /></a>
    </div>
    <div class="search_box">
      <form action="search.php" method="post">
        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." />
        <input type="submit" value="TÌM KIẾM" />
      </form>
    </div>
    <div class="header_right">
      <div class="shopping_cart">
        <a href="cart.php">Giỏ hàng <span>(trống)</span></a>
      </div>
      <div class="login">
        <a href="login.php">Đăng nhập</a>
      </div>
    </div>
</header>
</body>
</html>  </div>
  <div class="menu">
    <ul id="dc_mega-menu-orange">
      <li><a href="index.php">Trang chủ</a></li>
      <li><a href="products.php">Sản phẩm</a></li>
      <li><a href="#">Thương hiệu nổi bật</a></li>
      <li><a href="cart.php">Giỏ hàng</a></li>
      <li><a href="contact.php">Liên hệ</a></li>
    </ul>
  </div>
</div>

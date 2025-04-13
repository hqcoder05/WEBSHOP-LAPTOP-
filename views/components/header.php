<!DOCTYPE html>
<html lang="vi">
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
                <li><a href="index.php">Trang chủ</a></li>
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
    </div>
</header>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 20px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
        }

        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 1px solid #3498db;
            padding-bottom: 5px;
        }

        .footer-section p, .footer-section ul {
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul li a:hover {
            color: #3498db;
        }

        .footer-section iframe {
            width: 100%;
            height: 150px;
            border: none;
            border-radius: 5px;
        }

        .footer-bottom {
            text-align: center;
            padding: 15px 0;
            background-color: #1a252f;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<footer>
    <div class="footer-container">
        <!-- Thông tin liên hệ -->
        <div class="footer-section">
            <h3>Liên Hệ</h3>
            <p>Địa chỉ: 70 Tô Ký, Tân Chánh Hiệp, Q12, TP. HCM</p>
            <p>Email: shoplaptop@fake.com</p>
            <p>Hotline: 0909 123 456</p>
        </div>

        <!-- Liên kết nhanh -->
        <div class="footer-section">
            <h3>Liên Kết Nhanh</h3>
            <ul>
                <li><a href="../../index.php">Trang chủ</a></li>
                <li><a href="products.php">Sản phẩm</a></li>
                <li><a href="about.php">Giới thiệu</a></li>
                <li><a href="contact.php">Liên hệ</a></li>
            </ul>
        </div>

        <!-- Bản đồ -->
        <div class="footer-section">
            <h3>Vị Trí Cửa Hàng</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.28721634569!2d106.61554297485836!3d10.865745489288534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b2a11844fb9%3A0xbed3d5f0a6d6e0fe!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBHaWFvIFRow7RuZyBW4bqtbiBU4bqjaSBUaMOgbmggUGjhu5EgSOG7kyBDaMOtIE1pbmggKFVUSCkgLSBDxqEgc-G7nyAz!5e0!3m2!1svi!2s!4v1744945559874!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Bản quyền -->
    <div class="footer-bottom">
        <p>&copy; 2025 ShopLaptop. All Rights Reserved.</p>
    </div>
</footer>
</body>
</html>
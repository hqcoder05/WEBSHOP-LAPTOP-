<?php
include '../../pages/components/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Giới Thiệu</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo body chiếm toàn bộ chiều cao viewport */
        }
        .about-container {
            flex: 1; /* Chiếm không gian còn lại để đẩy footer xuống */
            min-height: 80vh; /* Đảm bảo khoảng cách tối thiểu trước footer */
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .about-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .about-content {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            line-height: 1.6;
        }
        .about-content h2 {
            color: #333;
            margin-top: 20px;
        }
        .about-content p {
            color: #666;
            margin: 10px 0;
        }
        .about-content ul {
            list-style: disc;
            padding-left: 20px;
            color: #666;
        }
        .about-content ul li {
            margin-bottom: 10px;
        }
        footer {
            background-color: #f8f8f8;
            padding: 10px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="about-container">
    <h1>Giới Thiệu Về Chúng Tôi</h1>
    <div class="about-content">
        <p>Chào mừng bạn đến với trang web của chúng tôi! Chúng tôi là một công ty tận tâm cung cấp các sản phẩm và dịch vụ chất lượng cao, đáp ứng nhu cầu của khách hàng trên toàn thế giới.</p>

        <h2>Sứ Mệnh Của Chúng Tôi</h2>
        <p>Chúng tôi cam kết mang lại giá trị vượt trội thông qua sự đổi mới, chất lượng và dịch vụ khách hàng xuất sắc. Mục tiêu của chúng tôi là xây dựng mối quan hệ lâu dài với khách hàng dựa trên sự tin cậy và tôn trọng.</p>

        <h2>Tại Sao Chọn Chúng Tôi?</h2>
        <ul>
            <li>Chất lượng sản phẩm được đảm bảo với tiêu chuẩn cao nhất.</li>
            <li>Đội ngũ hỗ trợ khách hàng chuyên nghiệp, sẵn sàng 24/7.</li>
            <li>Giá cả cạnh tranh và chính sách bảo hành rõ ràng.</li>
            <li>Cam kết bảo vệ môi trường và phát triển bền vững.</li>
        </ul>

        <p>Liên hệ với chúng tôi ngay hôm nay để biết thêm thông tin hoặc truy cập <a href="contact.php" style="color: #007bff; text-decoration: none;">trang liên hệ</a> của chúng tôi!</p>
    </div>
</div>

<?php
include '../../pages/components/footer.php';
?>
</body>
</html>
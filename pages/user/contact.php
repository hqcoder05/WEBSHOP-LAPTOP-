<?php
include '../../pages/components/header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Liên Hệ</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo body chiếm toàn bộ chiều cao viewport */
        }
        .contact-container {
            flex: 1; /* Chiếm không gian còn lại để đẩy footer xuống */
            min-height: 80vh; /* Đảm bảo khoảng cách tối thiểu trước footer */
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .contact-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .contact-form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
        }
        .contact-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        .contact-form textarea {
            resize: vertical;
            min-height: 100px;
        }
        .contact-form button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }
        .contact-form button:hover {
            background-color: #0056b3;
        }
        .contact-info {
            margin-top: 30px;
            text-align: center;
        }
        .contact-info p {
            margin: 10px 0;
            color: #666;
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
<div class="contact-container">
    <h1>Liên Hệ Với Chúng Tôi</h1>
    <div class="contact-form">
        <form action="process_contact.php" method="post">
            <label for="name">Họ và Tên</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="subject">Chủ Đề</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Tin Nhắn</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Gửi Tin Nhắn</button>
        </form>
    </div>
    <div class="contact-info">
        <p>Email: support@example.com</p>
        <p>Điện thoại: (123) 456-7890</p>
        <p>Địa chỉ: 123 Đường ABC, Thành phố XYZ</p>
    </div>
</div>

<?php
include '../../pages/components/footer.php';
?>
</body>
</html>
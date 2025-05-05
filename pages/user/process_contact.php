<?php
include '../../pages/components/header.php';
require_once '../../includes/db/database.php'; // Kết nối cơ sở dữ liệu
global $conn;

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_content = trim($_POST['message'] ?? '');

    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
        $error = 'Vui lòng điền đầy đủ tất cả các trường.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        // Chuẩn bị câu lệnh SQL để lưu dữ liệu
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $subject, $message_content);

        // Thực thi và kiểm tra kết quả
        if ($stmt->execute()) {
            $message = 'Tin nhắn của bạn đã được gửi thành công! Chúng tôi sẽ liên hệ lại sớm.';
        } else {
            $error = 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Xử Lý Liên Hệ</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo body chiếm toàn bộ chiều cao viewport */
        }
        .process-container {
            flex: 1; /* Chiếm không gian còn lại để đẩy footer xuống */
            min-height: 80vh; /* Đảm bảo khoảng cách tối thiểu trước footer */
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .process-container h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .message-box {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .message-box.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .message-box a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .message-box a:hover {
            text-decoration: underline;
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
<div class="process-container">
    <h1>Kết Quả Gửi Liên Hệ</h1>
    <div class="message-box <?php echo $message ? 'success' : 'error'; ?>">
        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php elseif ($error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <p><a href="contact.php">Quay lại trang liên hệ</a></p>
    </div>
</div>

<?php
include '../../pages/components/footer.php';
?>
</body>
</html>
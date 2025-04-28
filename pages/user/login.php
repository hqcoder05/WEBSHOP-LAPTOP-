<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
require_once __DIR__ . '/../components/header.php';
?>
<link rel="stylesheet" href="../../assets/css/login.css">
<div class="login-page">
    <div class="container">
        <h2>Đăng nhập</h2>
        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form action="../../includes/logic/login_function.php" method="POST">
            <label for="username">Tài khoản:</label><br/>
            <input type="text" id="username" name="username" required><br/>
            <label for="password">Mật khẩu:</label><br/>
            <input type="password" id="password" name="password" required><br/>
            <button type="submit">Đăng nhập</button>
            <p>Chưa có tài khoản? <a href="../user/register.php">Tạo tài khoản mới</a></p>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

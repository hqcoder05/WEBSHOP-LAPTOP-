<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}
require_once __DIR__ . '/../components/header.php';
?>
<link rel="stylesheet" href="../../assets/css/register.css">
<div class="register-page">
    <div class="container">
        <h2>Đăng ký tài khoản</h2>
        <?php if (!empty($_SESSION['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <form action="/includes/logic/register_function.php" method="post">
            <label>Username:</label><br>
            <input type="text" name="username" required><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br>
            <label>Nhập lại Password:</label><br>
            <input type="password" name="confirm_password" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br>
            <button type="submit">Đăng ký</button>
            <p>Đã có tài khoản <a href="../user/login.php">Đăng nhập</a></p>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>

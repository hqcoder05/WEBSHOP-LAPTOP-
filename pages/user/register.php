<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}
require_once __DIR__ . '/../../pages/components/header.php';
?>

    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/login.css">

    <div class="login-page">
        <div class="container">
            <h2>Đăng ký tài khoản</h2>
            <?php if (!empty($_SESSION['error'])): ?>
                <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
            <?php endif; ?>
            <form action="../../includes/logic/register_function.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Nhập lại Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <button type="submit">Đăng ký</button>
            </form>
        </div>
    </div>

<?php require_once __DIR__ . '/../../pages/components/footer.php'; ?>
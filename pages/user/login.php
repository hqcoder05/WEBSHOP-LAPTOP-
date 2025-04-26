<?php session_start(); ?>
<form action="../../includes/logic/login_function.php" method="POST">
    <h2>Đăng nhập</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <label>Tài khoản:</label><br/>
    <input type="text" name="username"><br/>

    <label>Mật khẩu:</label><br/>
    <input type="password" name="password"><br/>

    <button type="submit">Đăng nhập</button>
    <a>Chưa có tài khoản?</a> <a href="register.php">Tạo tài khoản mới</a>
</form>

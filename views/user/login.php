<?php include __DIR__ . '/../components/header.php'; ?>

<div class="login-container">
    <h2>Đăng nhập</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login">
        <div>
            <label for="username">Tên đăng nhập:</label><br>
            <input type="text" name="username" id="username" required>
        </div><br>

        <div>
            <label for="password">Mật khẩu:</label><br>
            <input type="password" name="password" id="password" required>
        </div><br>

        <button type="submit">Đăng nhập</button>
    </form>

    <p>Chưa có tài khoản? <a href="index.php?page=register">Đăng ký</a></p>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

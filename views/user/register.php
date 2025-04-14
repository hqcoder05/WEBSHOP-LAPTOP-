<?php include __DIR__ . '/../components/header.php'; ?>
<link rel="stylesheet" href="../../wwwroot/css/user/register.css"/>
    <div class="register-container">
        <h2>Đăng ký tài khoản</h2>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="../../index.php?page=register">
            <div>
                <label for="username">Tên đăng nhập:</label><br>
                <input type="text" name="username" id="username" required>
            </div><br>

            <div>
                <label for="email">Email:</label><br>
                <input type="text" name="email" id="email" required>
            </div><br>

            <div>
                <label for="password">Mật khẩu:</label><br>
                <input type="password" name="password" id="password" required>
            </div><br>

            <div>
                <label for="confirm_password">Xác nhận mật khẩu:</label><br>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div><br>

            <button type="submit">Đăng ký</button>
        </form>

        <p>Đã có tài khoản? <a href="../user/login.php">Đăng nhập</a></p>
    </div>

<?php include __DIR__ . '/../components/footer.php'; ?>
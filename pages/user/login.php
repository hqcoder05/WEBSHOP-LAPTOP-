<?php include_once '../../includes/autoload.php'; ?>
<?php include_once '../../pages/components/header.php'; ?>
<script src="../../assets/js/jquery-3.7.1.js"></script>
<script src="../../assets/js/user_login.js"></script>

<h2>Đăng nhập</h2>
<form id="loginForm">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" name="username" id="username" required>

    <label for="password">Mật khẩu:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Đăng nhập</button>
</form>

<div id="loginMessage"></div>

<?php include_once '../components/footer.php'; ?>

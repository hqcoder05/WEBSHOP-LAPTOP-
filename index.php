<?php include __DIR__ . '/pages/components/header.php'; ?>
<?php
session_start();
require_once 'includes/autoload.php';

$page = $_GET['page'] ?? 'user/home';
$pagePath = "pages/$page.php";

if (file_exists($pagePath)) {
    include "pages/components/header.php";
    include "pages/components/navbar.php";
    include $pagePath;
    include "pages/components/footer.php";
} else {
    echo "<h1>404 - Trang không tồn tại</h1>";
}


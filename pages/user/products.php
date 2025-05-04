<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';

// Đảm bảo CSRF token tồn tại
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 12;
$offset = ($page - 1) * $items_per_page;

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : null;

$categories = getAllCategories();

if ($category_id !== null && $category_id > 0) {
    $products = getProductsByCategory($category_id, $items_per_page, $offset);
    $total_products = countProducts($category_id);
} elseif ($search_keyword !== null && $search_keyword !== '') {
    $products = searchProducts($search_keyword);
    $total_products = count($products);
    $products = array_slice($products, $offset, $items_per_page);
} else {
    $products = getAllProducts($items_per_page, $offset);
    $total_products = countProducts();
}

$total_pages = ceil($total_products / $items_per_page);

$page_title = "Tất cả sản phẩm";
if ($category_id !== null && $category_id > 0) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $page_title = "Sản phẩm: " . $cat['name'];
            break;
        }
    }
} elseif ($search_keyword !== null && $search_keyword !== '') {
    $page_title = "Kết quả tìm kiếm: " . htmlspecialchars($search_keyword);
}

require_once __DIR__ . '/../components/header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="../../assets/css/user_products.css" rel="stylesheet"/>
    <script src="../../assets/js/jquery-3.7.1.js"></script>
</head>
<body>
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="column sidebar">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Danh mục</h5>
                </div>
                <div class="card-body">
                    <ul class="list">
                        <li class="list-item <?php echo ($category_id === null) ? 'active' : ''; ?>">
                            <a href="products.php" class="text-decoration-none <?php echo ($category_id === null) ? 'text-white' : 'text-dark'; ?>">Tất cả sản phẩm</a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li class="list-item <?php echo ($category_id == $category['id']) ? 'active' : ''; ?>">
                                <a href="products.php?category=<?php echo $category['id']; ?>" class="text-decoration-none <?php echo ($category_id == $category['id']) ? 'text-white' : 'text-dark'; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tìm kiếm</h5>
                </div>
                <div class="card-body">
                    <form action="../user/products.php" method="GET">
                        <div class="input-group">
                            <input type="text" class="input-text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search_keyword ?? ''); ?>">
                            <button class="button button-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Cột 2: Banner -->
            <div class="banner">
                <?php include '../../pages/components/banner.php'; ?>
            </div>
        </div>
        <div class="column main">
            <div class="flex justify-between align-center mb-4">
                <h2><?php echo htmlspecialchars($page_title); ?></h2>
                <span>Hiển thị <?php echo min($total_products, $items_per_page); ?> / <?php echo $total_products; ?> sản phẩm</span>
            </div>
            <?php if (empty($products)): ?>
                <div class="alert alert-info">
                    Không tìm thấy sản phẩm phù hợp với từ khóa "<?= htmlspecialchars($search_keyword ?? '') ?>".
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-container">
                                <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : '../../assets/images/products/default.jpg'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title product-title">
                                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h5>
                                <p class="card-text product-category">
                                    <small class="text-muted">
                                        <a href="products.php?category=<?php echo $product['category_id']; ?>" class="text-decoration-none text-muted">
                                            <?php echo htmlspecialchars($product['category_name']); ?>
                                        </a>
                                    </small>
                                </p>
                                <p class="card-text product-price fw-bold text-danger">
                                    <?php echo formatPrice($product['price']); ?>
                                </p>
                                <p class="card-text product-stock">
                                    <small class="<?php echo $product['stock'] > 0 ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo $product['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                                    </small>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="flex justify-between align-center">
                                    <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="button button-outline">Chi tiết</a>
                                    <?php if ($product['stock'] > 0): ?>
                                        <form action="pages/user/checkout.php" method="POST" class="form-buy-now">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="input-number" required>
                                            <button type="submit" class="button button-primary">Đặt hàng ngay</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="button button-disabled" disabled>Hết hàng</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($total_pages > 1): ?>
                    <nav class="pagination-nav" aria-label="Điều hướng trang">
                        <ul class="pagination-list">
                            <li class="pagination-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="pagination-link" href="<?php
                                $params = $_GET;
                                $params['page'] = $page - 1;
                                echo 'products.php?' . http_build_query($params);
                                ?>" aria-label="Trước">
                                    <span aria-hidden="true">«</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="pagination-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="pagination-link" href="<?php
                                    $params = $_GET;
                                    $params['page'] = $i;
                                    echo 'products.php?' . http_build_query($params);
                                    ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="pagination-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="pagination-link" href="<?php
                                $params = $_GET;
                                $params['page'] = $page + 1;
                                echo 'products.php?' . http_build_query($params);
                                ?>" aria-label="Tiếp">
                                    <span aria-hidden="true">»</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
<?php
session_start();
require_once __DIR__ . '/../../includes/db/database.php';
require_once __DIR__ . '/../../includes/logic/product_functions.php';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Danh mục</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item <?php echo ($category_id === null) ? 'active' : ''; ?>">
                            <a href="/Laptop_Shop - Copy/pages/user/products.php" class="text-decoration-none <?php echo ($category_id === null) ? 'text-white' : 'text-dark'; ?>">Tất cả sản phẩm</a>
                        </li>
                        <?php foreach ($categories as $category): ?>
                            <li class="list-group-item <?php echo ($category_id == $category['id']) ? 'active' : ''; ?>">
                                <a href="/Laptop_Shop - Copy/pages/user/products.php?category=<?php echo $category['id']; ?>" class="text-decoration-none <?php echo ($category_id == $category['id']) ? 'text-white' : 'text-dark'; ?>">
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
                    <form action="/Laptop_Shop - Copy/pages/user/products.php" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($search_keyword ?? ''); ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Cột 2: Banner -->
            <div class="col-lg-3">
            <?php
           include '../../pages/components/banner.php';
            ?>
        </div>
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
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
                                <img src="<?php echo !empty($product['image']) ? htmlspecialchars($product['image']) : '/Laptop_Shop - Copy/assets/images/products/default.jpg'; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title product-title">
                                    <a href="/Laptop_Shop - Copy/pages/user/product_detail.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h5>
                                <p class="card-text product-category">
                                    <small class="text-muted">
                                        <a href="/Laptop_Shop - Copy/pages/user/products.php?category=<?php echo $product['category_id']; ?>" class="text-decoration-none text-muted">
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
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between">
                                    <a href="/Laptop_Shop - Copy/pages/user/product_detail.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                    <?php if ($product['stock'] > 0): ?>
                                        <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?php echo $product['id']; ?>">Thêm vào giỏ</button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>Hết hàng</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Điều hướng trang">
                        <ul class="pagination justify-content-center mt-4">
                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php
                                $params = $_GET;
                                $params['page'] = $page - 1;
                                echo '/Laptop_Shop - Copy/pages/user/products.php?' . http_build_query($params);
                                ?>" aria-label="Trước">
                                    <span aria-hidden="true">«</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php
                                    $params = $_GET;
                                    $params['page'] = $i;
                                    echo '/Laptop_Shop - Copy/pages/user/products.php?' . http_build_query($params);
                                    ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?php
                                $params = $_GET;
                                $params['page'] = $page + 1;
                                echo '/Laptop_Shop - Copy/pages/user/products.php?' . http_build_query($params);
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
<script src="/Laptop_Shop - Copy/assets/js/user_products.js"></script>
<?php require_once __DIR__ . '/../components/footer.php'; 
?>
</body>
</html>
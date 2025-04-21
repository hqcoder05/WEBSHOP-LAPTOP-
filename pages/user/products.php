<?php
  include '../../pages/components/header.php';
  include '../../pages/components/slider.php';
?>
<style>
  .main {
     width: 100%;
    padding: 0 30px;
    box-sizing: border-box;
  }
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    .section-group {
      overflow: hidden;
    }

    .product-card {
      width: 22%;
      float: left;
      margin: 1%;
      padding: 1.5%;
      text-align: center;
      box-shadow: 0 0 3px rgb(150, 150, 150);
      position: relative;
    }

    .product-card img {
      max-width: 100%;
      height: auto;
    }

    .product-card h2 {
      color: #cc3636;
      font-size: 18px;
      margin: 10px 0;
    }

    .product-card p {
      font-size: 0.875em;
      color: #333;
      margin: 5px 0;
    }

    .product-card .price {
      font-size: 18px;
      font-weight: bold;
    }

    .product-card .button {
      margin-top: 10px;
    }

    .product-card .button a {
      display: inline-block;
      padding: 7px 20px;
      font-size: 14px;
      text-decoration: none;
      border: 1px solid #c1c1c1;
      border-radius: 3px;
      transition: all 0.3s ease;
    }

    .product-card .button a:hover {
      background-color: #e8e8e8;
      color: #70389c;
    }

    .content {
      width: 100%;
      padding: 20px;
    }

    .heading h3 {
      font-size: 20px;
      color: #333;
      margin-bottom: 20px;
    }

    .clear {
      clear: both;
    }
  </style>

<div class="main">
  <div class="content">
    <div class="content_top">
      <div class="heading"><h3>LAPTOP MỚI NHẤT</h3></div>
      <div class="clear"></div>
    </div>

    <div class="section-group">
      <div class="product-card">
        <a href="product_detail-3.php"><img src="../../assets/images/products/fic1.png" style="width:180px; height:200px;" alt="Lenovo" /></a>
        <h2>Lenovo đơn giản.</h2>
        <p>Cảm ơn quý khách.</p>
        <p><span class="price">$505.22</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <a href="product_detail-2.php"><img src="../../assets/images/products/fic2.jpg" style="width:200px; height:200px;" alt="Acer" /></a>
        <h2>Acer đơn giản.</h2>
        <p>Cảm ơn quý khách.</p>
        <p><span class="price">$620.87</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <a href="product_detail-4.php"><img src="../../assets/images/products/fic3.jpg" style="width:180px; height:200px;" alt="Macbook" /></a>
        <h2>Macbook đơn giản.</h2>
        <p>Cảm ơn quý khách.</p>
        <p><span class="price">$220.97</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <img src="../../assets/images/products/fic4.png" style="width:180px; height:200px;" alt="Asus" />
        <h2>Asus Vivobook đơn giản</h2>
        <p>Cảm ơn quý khách.</p>
        <p><span class="price">$415.54</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>
    </div>

    <div class="content_bottom">
      <div class="heading"><h3>PC, MÀN HÌNH, PHỤ KIỆN MỚI NHẤT</h3></div>
      <div class="clear"></div>
    </div>

    <div class="section-group">
      <div class="product-card">
        <a href="product_detail-3.php"><img src="../../assets/images/products/new_fic1.jpg" style="width:230px; height:190px;" alt="Màn hình" /></a>
        <h2>Màn hình đơn giản.</h2>
        <p><span class="price">$403.66</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <a href="product_detail-4.php"><img src="../../assets/images/products/new_fic2.jpg" style="width:200px; height:190px;" alt="Máy in" /></a>
        <h2>Máy in đơn giản.</h2>
        <p><span class="price">$621.75</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <a href="product_detail-2.php"><img src="../../assets/images/products/new_fic3.jpg" style="width:200px; height:190px;" alt="Màn hình PC" /></a>
        <h2>Màn hình PC đơn giản.</h2>
        <p><span class="price">$428.02</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>

      <div class="product-card">
        <img src="../../assets/images/products/new_fic4.jpg" style="width:230px; height:190px;" alt="Màn hình Gaming" />
        <h2>Màn hình Gaming đơn giản.</h2>
        <p><span class="price">$457.88</span></p>
        <div class="button"><a href="../../pages/user/product_detail.php">Chi tiết</a></div>
      </div>
    </div>
  </div>
</div>
<?php
include('../../pages/components/footer.php');
?>


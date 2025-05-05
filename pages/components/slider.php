<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Slider</title>
  <style>
    /* Thêm khoảng cách giữa slider và sản phẩm */
    .header_bottom {
      margin-top: 30px;
      padding: 0 40px;
    }

    .section.group {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 30px;
    }

    .listview_1_of_2 {
      background: #fff;
      border: 1px solid #ddd;
      padding: 15px;
      width: 48%;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      gap: 15px;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .listimg img {
      width: 120px;
      height: auto;
      object-fit: contain;
    }

    .text {
      flex: 1;
    }

    .text h2 {
      color: #c0392b;
      margin-bottom: 6px;
      font-size: 18px;
    }

    .text p {
      font-size: 14px;
      margin-bottom: 10px;
    }

    .button a {
      background: #6c3483;
      color: white;
      padding: 6px 15px;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
    }

    .button a:hover {
      background: #512e5f;
    }

    .clear {
      clear: both;
    }
  </style>
</head>
<body>
  <!-- DANH SÁCH SẢN PHẨM -->
  <div class="header_bottom">
    <div class="header_bottom_left">
      <div class="section group">
        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="product_detail.php"><img src="../../assets/images/products/h8.png" alt="Acer"/></a>
          </div>
          <div class="text">
            <h2>Acer</h2>
            <p>Quý khách hãy chọn lựa.</p>
            <div class="button"><a href="product_detail.php">Thêm vào giỏ hàng</a></div>
          </div>
        </div>

        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="product_detail.php"><img src="../../assets/images/products/h6.png" alt="Dell"/></a>
          </div>
          <div class="text">
            <h2>Dell</h2>
            <p>Quý khách hãy chọn lựa.</p>
            <div class="button"><a href="product_detail.php">Thêm vào giỏ hàng</a></div>
          </div>
        </div>
      </div>

      <div class="section group">
        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="product_detail.php"><img src="../../assets/images/products/product09.jpg" alt="HP"/></a>
          </div>
          <div class="text">
            <h2>HP</h2>
            <p>Quý khách hãy chọn lựa.</p>
            <div class="button"><a href="product_detail.php">Thêm vào giỏ hàng</a></div>
          </div>
        </div>

        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="product_detail.php"><img src="../../assets/images/products/product05.png" alt="Asus"/></a>
          </div>
          <div class="text">
            <h2>Asus</h2>
            <p>Quý khách hãy chọn lựa.</p>
            <div class="button"><a href="product_detail.php">Thêm vào giỏ hàng</a></div>
          </div>
        </div>
      </div>

      <div class="clear"></div>
    </div>

    <div class="header_bottom_right_images">
      <!-- Nếu có ảnh khác -->
    </div>
  </div>

</body>
</html>

</body>
</html>
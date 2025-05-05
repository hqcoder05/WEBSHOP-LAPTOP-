<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Banner</title>
  <style>
  .header_bottom {
    margin-top: 33px; 
  }

  .header_bottom_left {
    margin: auto;
    width: 120%; /* Thay đổi độ dài ảnh */
    padding-bottom: 6px;
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
    border: 1px solid #ddd; /* Đã chỉnh mỏng hơn */
    padding: 15px;
    width: 48%;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

  .listimg img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
  }

  .text {
    flex: 1;
  }

  .text h2 {
    color: #007bff;
    font-weight: bold;
    margin-bottom: 6px;
    font-size: 16px;
  }

  .text p {
    font-size: 13px;
    margin-bottom: 8px;
  }

  .button a {
    background: white;
    color: #007bff;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 13px;
    display: inline-block;
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
            <a href="../../pages/user/products.php?category=1"><img src="../../assets/images/logo/Dell-Logo.png" alt="Dell"/></a>
          </div>
          <div class="text">
            <h2>Dell</h2>
            <p>Còn hàng.</p>
          </div>
        </div>

        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="../../pages/user/products.php?category=3"><img src="../../assets/images/logo/HP-Logo.png" alt="HP"/></a>
          </div>
          <div class="text">
            <h2>HP</h2>
            <p>Còn hàng</p>
          </div>
        </div>
      </div>

      <div class="section group">
        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="../../pages/user/products.php?category=2"><img src="../../assets/images/logo/Lenovo-logo.png" alt="Lenovo"/></a>
          </div>
          <div class="text">
            <h2>Lenovo</h2>
            <p>Còn hàng.</p>
          </div>
        </div>

        <div class="listview_1_of_2">
          <div class="listimg">
            <a href="../../pages/user/products.php?category=4"><img src="../../assets/images/logo/Apple.png" alt="Macbook"/></a>
          </div>
          <div class="text">
            <h2>Macbook</h2>
            <p>Còn hàng.</p>
          </div>
        </div>
      </div>

      <div class="clear"></div>
    </div>

    <div class="header_bottom_right_images">
    </div>
  </div>

</body>
</html>

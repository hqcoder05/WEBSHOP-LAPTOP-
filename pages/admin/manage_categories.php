<?php
  include '../../pages/admin/admin_header.php'
?>

  <div class="admin-container">
    <h1>Quản lý danh mục</h1>
    <form class="form-section">
      <label for="new_category">Chọn danh mục:</label>
      <select id="new_category" name="new_category">
        <option value="Dell">Dell</option>
        <option value="Lenovo">Lenovo</option>
        <option value="HP">HP</option>
        <option value="Macbook">Macbook</option>
      </select>
      <button type="submit">Thêm danh mục</button>
    </form>
  </div>

<?php
  include '../../pages/admin/admin_footer.php'
?>
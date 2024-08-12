<?php
require 'header.php';
?>

<main>
  <h2>在庫レベル通知設定</h2>
  <form action="notification_process.php" method="post">
    <ul>
      <li>
        <label for="item_id">アイテム:</label>
        <select id="item_id" name="item_id" required>
          <option value="1">商品A</option>
          <option value="2">商品B</option>
        </select>
      </li>
      <li>
        <label for="threshold">しきい値:</label>
        <input type="number" id="threshold" name="threshold" required>
      </li>
      <li>
        <label for="notify">通知:</label>
        <input type="checkbox" id="notify" name="notify">
      </li>
      <li>
        <button type="submit">保存</button>
      </li>
    </ul>
  </form>
</main>
<?php
require 'footer.php';
?>
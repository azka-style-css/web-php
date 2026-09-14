<h2>Items</h2>

<form class="crud-form" data-resource="items">
    <input type="hidden" name="id">
    <input type="text" name="item_name" placeholder="Item name" maxlength="100" required>
    <input type="number" name="price" placeholder="Price" min="0" step="0.01" required>
    <input type="number" name="quantity" placeholder="Stock" min="0" step="1" required>
    <button type="submit" class="settings-button" data-submit-label>Add item</button>
    <button type="button" class="settings-button" data-cancel-edit hidden>Cancel</button>
    <p class="crud-message" aria-live="polite"></p>
</form>

<table>
      <tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Item Price</th>
          <th>Item Stock</th>
          <th>Actions</th>
      </tr>

      <?php
      $connection = require __DIR__ . '/../config/database.php';

      $result = mysqli_query($connection, "SELECT * FROM items");

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr data-id='" . htmlspecialchars($row['id_item']) . "' data-name='" . htmlspecialchars($row['item_name'], ENT_QUOTES) . "' data-price='" . htmlspecialchars($row['price']) . "' data-quantity='" . htmlspecialchars($row['quantity']) . "'>";
              echo "<td>" . htmlspecialchars($row['id_item']) . "</td>";
              echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['price']) . "</td>";
              echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
              echo "<td><button type='button' class='settings-button' data-edit-row>Edit</button> <button type='button' class='settings-button' data-delete-id='" . htmlspecialchars($row['id_item']) . "'>Delete</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No items found</td></tr>";
      }

      if ($connection) mysqli_close($connection);
      ?>
</table>
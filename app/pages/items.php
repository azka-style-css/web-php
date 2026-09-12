<h2>Items Tables</h2>

<table>
      <tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Item Price</th>
          <th>Item Stock</th>
      </tr>

      <?php
      $connection = require __DIR__ . '/../config/database.php';

      $result = mysqli_query($connection, "SELECT * FROM items");

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['id_item'] . "</td>";
              echo "<td>" . $row['item_name'] . "</td>";
              echo "<td>" . $row['price'] . "</td>";
              echo "<td>" . $row['quantity'] . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='4'>No items found</td></tr>";
      }

      if ($connection) mysqli_close($connection);
      ?>
</table>
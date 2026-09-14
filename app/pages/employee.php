<h2>Employees</h2>

<form class="crud-form" data-resource="employees">
    <input type="hidden" name="id">
    <input type="text" name="name" placeholder="Name" maxlength="100" required>
    <input type="text" name="addres" placeholder="Address" maxlength="255" required>
    <input type="text" name="username" placeholder="Username" maxlength="50" required>
    <input type="text" name="password" placeholder="Password" minlength="3" required>
    <button type="submit" class="settings-button" data-submit-label>Add employee</button>
    <button type="button" class="settings-button" data-cancel-edit hidden>Cancel</button>
    <p class="crud-message" aria-live="polite"></p>
</form>

<table>
      <tr>
          <th>NIP</th>
          <th>Name</th>
          <th>Address</th>
          <th>Username</th>
          <th>Password</th>
          <th>Actions</th>
      </tr>

      <?php
      $connection = require __DIR__ . '/../config/database.php';

      $result = mysqli_query($connection, "SELECT * FROM employee");

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr data-id='" . htmlspecialchars($row['nip']) . "' data-name='" . htmlspecialchars($row['name'], ENT_QUOTES) . "' data-addres='" . htmlspecialchars($row['addres'], ENT_QUOTES) . "' data-username='" . htmlspecialchars($row['username'], ENT_QUOTES) . "' data-password='" . htmlspecialchars($row['password'], ENT_QUOTES) . "'>";
              echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['addres']) . "</td>";
              echo "<td>" . htmlspecialchars($row['username']) . "</td>";
              echo "<td>" . htmlspecialchars($row['password']) . "</td>";
              echo "<td><button type='button' class='settings-button' data-edit-row>Edit</button> <button type='button' class='settings-button' data-delete-id='" . htmlspecialchars($row['nip']) . "'>Delete</button></td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='6'>No employees found</td></tr>";
      }

      if ($connection) mysqli_close($connection);
      ?>
</table>
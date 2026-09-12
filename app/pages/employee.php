<h2>Employees Tables</h2>

<table>
      <tr>
          <th>NIP</th>
          <th>Name</th>
          <th>Address</th>
          <th>Username</th>
          <th>Password</th>
      </tr>

      <?php
      $connection = require __DIR__ . '/../config/database.php';

      $result = mysqli_query($connection, "SELECT * FROM employee");

      if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['nip'] . "</td>";
              echo "<td>" . $row['name'] . "</td>";
              echo "<td>" . $row['addres'] . "</td>";
              echo "<td>" . $row['username'] . "</td>";
              echo "<td>" . $row['password'] . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No employees found</td></tr>";
      }

      if ($connection) mysqli_close($connection);
      ?>
</table>
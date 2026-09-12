<?php
require_once __DIR__ . '/../auth.php';
requireLogin();

$username = currentUser();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../public/css/dashboard.css?v=3" />
  </head>
  <body>
    <div class="app-shell">
      <aside class="sidebar">
        <div class="brand">Dashboard</div>
        <nav class="nav-menu">
          <a href="#" class="nav-item active" data-page="home.php">Home</a>
          <a href="#" class="nav-item" data-page="items.php">Items</a>
          <a href="#" class="nav-item" data-page="employee.php">Employees</a>
        </nav>

        <div class="user-box">
          <span class="user-label">Logged in as</span>
          <strong><?php echo htmlspecialchars($username); ?></strong>
        </div>

        <a class="logout-btn" href="../../logout.php">Logout</a>
      </aside>

      <main class="main-panel">
        <header class="topbar">
          <h1>Dashboard</h1>
        </header>

        <section id="main-content" class="content-area"></section>
      </main>
    </div>

    <script src="../../index.js"></script>
  </body>
</html>

<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: /azka/app/pages/dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../../public/css/login.css" />
  </head>
  <body>
    <form id="loginForm">
      <div id="error-message" style="color: red; display: none; margin-bottom: 12px"></div>
      <h2>Login</h2>
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required /><br /><br />
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required /><br /><br />
      <input type="submit" value="Login" />
    </form>

    <script>
      const form = document.getElementById('loginForm');
      const errorMessage = document.getElementById('error-message');

      form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!username || !password) {
          errorMessage.textContent = 'Username dan password harus diisi.';
          errorMessage.style.display = 'block';
          return;
        }

        try {
          const response = await fetch('../../authenticate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: new URLSearchParams({ username, password }).toString(),
          });

          const result = await response.text();

          if (result.trim() === 'success') {
            window.location.href = 'dashboard.php';
            return;
          }

          errorMessage.textContent = 'Username atau password salah.';
          errorMessage.style.display = 'block';
        } catch (error) {
          errorMessage.textContent = 'Terjadi kesalahan saat login.';
          errorMessage.style.display = 'block';
        }
      });
    </script>
  </body>
</html>

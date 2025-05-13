<?php
// login.php

session_start();
require_once 'db_connect.php'; // Include DB connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND role = 'admin'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $user, $db_password);
            $stmt->fetch();

            if ($password === $db_password) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $user;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found or not an admin.";
        }
        $stmt->close();
    } else {
        $error = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="custom_css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" placeholder="Enter username" required autofocus>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" placeholder="Enter password" required>

      <button type="submit">Login</button>
    </form>
  </div>
  <script src="scripts.js"></script>
</body>

</html>

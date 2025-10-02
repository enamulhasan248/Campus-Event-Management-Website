<?php
session_start();
$showError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['password']) {
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $row['usertype'];
            $_SESSION['id'] = $row['id'];

            if ($row['usertype'] === 'normal_user') {
                header("Location: normal_user.php");
                exit();
            } elseif ($row['usertype'] === 'admin') {
                header("Location: admin.php");
                exit();
            } else {
                $showError = "Unknown user type.";
            }
        } else {
            $showError = "Incorrect password.";
        }
    } else {
        $showError = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Event Manager</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="login.css" rel="stylesheet" />
</head>
<body>
  <div class="form-box">
    <h1>Welcome Back</h1>
    <p>Login to your account</p>

    <?php if (!empty($showError)): ?>
      <div class="error-message"><?= htmlspecialchars($showError) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="usernameInput">Enter Username</label>
      <input type="text" id="usernameInput" name="username" placeholder="Enter your username" required>

      <label for="passwordInput">Password</label>
      <input type="password" id="passwordInput" name="password" placeholder="Enter your password" required>

      <button type="submit">Login</button>
    </form>

    <div class="register-link">
      Don’t have an account? <a href="signup.php">Register here</a>
    </div>
  </div>
</body>
</html>

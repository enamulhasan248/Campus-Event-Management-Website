<?php
$showAlert = false;
$showError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $id = mysqli_real_escape_string($conn, $_POST["id"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $type = "normal_user";

    $sql = "SELECT * FROM users WHERE username = '$username' OR id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $showError = "Username or ID already exists.";
    } else {
        if ($password === $confirm_password) {
            $sql_insert = "INSERT INTO users (username, usertype, id, password, date) VALUES ('$username', '$type', '$id', '$password', current_timestamp())";
            if (mysqli_query($conn, $sql_insert)) {
                $showAlert = true;
            } else {
                $showError = "Database insertion error: " . mysqli_error($conn);
            }
        } else {
            $showError = "Passwords do not match.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Signup</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #a1c4fd, #c2e9fb); /* Blue gradient */
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .signup-box {
      background-color: white;
      padding: 35px 40px;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #1e3a8a; /* Dark blue */
    }

    .btn-primary {
      background-color: #1e3a8a;
      border: none;
      font-weight: 600;
      border-radius: 25px;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #2563eb; /* Lighter blue on hover */
    }

    .alert {
      border-radius: 10px;
    }

    a {
      color: #1e3a8a; /* Dark blue for link */
      font-weight: 500;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Optional: Form input field design */
    .form-control {
      border-radius: 10px;
      border: 1px solid #ccd6f6;
      padding: 12px;
    }

    .form-control:focus {
      border-color: #61dafb;
      box-shadow: 0 0 8px rgba(97, 218, 251, 0.7);
    }
  </style>
</head>
<body>

<div class="signup-box">
  <h2>Create Your Account</h2>

  <?php 
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Signup completed.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if (!empty($showError)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> ' . htmlspecialchars($showError) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
  ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="usernameInput" class="form-label">Username</label>
      <input type="text" class="form-control" id="usernameInput" name="username" placeholder="Enter username" required>
    </div>

    <div class="mb-3">
      <label for="idInput" class="form-label">ID</label>
      <input type="text" class="form-control" id="idInput" name="id" placeholder="Enter ID" required>
    </div>

    <div class="mb-3">
      <label for="passwordInput" class="form-label">Password</label>
      <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Enter password" required>
    </div>

    <div class="mb-3">
      <label for="confirmPasswordInput" class="form-label">Confirm Password</label>
      <input type="password" class="form-control" id="confirmPasswordInput" name="confirm_password" placeholder="Confirm password" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Register</button>

    <p class="text-center mt-3">
      Already have an account? <a href="login.php">Login here</a>
    </p>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

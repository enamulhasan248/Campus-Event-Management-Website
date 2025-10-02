<?php

session_start();

if (!isset($_SESSION['id'])) {
    
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['id']; 
$user_name = $_SESSION['username'];

// Include your database connection file
include 'partials/_dbconnect.php';

// Initialize variables
$tshirt_size = '';
$photo = 'uploads/default.jpg';  // Default image for the profile photo


$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If the user has a profile photo, use it; otherwise, keep the default one
if (!empty($user['user_photo'])) {
    $photo = $user['user_photo'];  // Current profile photo
}
$tshirt_size = $user['tshirt_size'];  // Current t-shirt size

// Handle form submission for updating user info
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted data
    $tshirt_size = $_POST['tshirt_size'];

    // Handle photo upload if a new photo is provided
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == 0) {
        $photo_tmp = $_FILES['user_photo']['tmp_name'];
        $photo_name = $_FILES['user_photo']['name'];
        $photo_path = 'uploads/' . basename($photo_name);
        
        // Move the uploaded photo to the 'uploads' folder
        if (move_uploaded_file($photo_tmp, $photo_path)) {
            // Update the user's profile photo and t-shirt size in the database
            $query = "UPDATE users SET user_photo = ?, tshirt_size = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $photo_path, $tshirt_size, $user_id);
            $stmt->execute();
            
            // Redirect to normal_user.php after updating
            header("Location: normal_user.php");
            exit();
        } else {
            echo "Error uploading photo.";
        }
    } else {
        // If no new photo is uploaded, only update the t-shirt size (allow NULL)
        $query = "UPDATE users SET tshirt_size = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $tshirt_size, $user_id);
        $stmt->execute();
        
        // Redirect to normal_user.php after updating
        header("Location: normal_user.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Customize Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href ="customize.css" rel="stylesheet" />

</head>
<body>

<div class="container">
  <div class="header">
    <h3>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h3>
  </div>

  <h2>Update Your Profile</h2>


  <form action="customize.php" method="POST" enctype="multipart/form-data">
    
    <!-- Display current profile photo or default image -->
    <div class="mb-3 text-center">
      <label for="user_photo" class="form-label">Profile Photo</label>
      <div>
        <img src="<?php echo ($photo ? $photo : 'uploads/default.jpg'); ?>" alt="User Photo" class="profile-image">
      </div>
      <input type="file" class="form-control" name="user_photo" id="user_photo">
      <small class="form-text text-muted">Upload a new photo (optional).</small>
    </div>

    
    <div class="mb-3">
      <label for="tshirt_size" class="form-label">T-shirt Size</label>
      <select class="form-select" name="tshirt_size" id="tshirt_size">
        <option value="S" <?php echo ($tshirt_size == 'S' ? 'selected' : ''); ?>>S</option>
        <option value="M" <?php echo ($tshirt_size == 'M' ? 'selected' : ''); ?>>M</option>
        <option value="L" <?php echo ($tshirt_size == 'L' ? 'selected' : ''); ?>>L</option>
        <option value="XL" <?php echo ($tshirt_size == 'XL' ? 'selected' : ''); ?>>XL</option>
        <option value="XXL" <?php echo ($tshirt_size == 'XXL' ? 'selected' : ''); ?>>XXL</option>
      </select>
    </div>

    
    <button type="submit" class="btn btn-primary">Update Profile</button>
  </form>
</div>

</body>
</html>
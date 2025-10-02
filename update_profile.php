<?php
// Include your database connection file
include 'db_connect.php';

// Get the form data
$tshirt_size = $_POST['tshirt_size'];
$user_id = '2021-1-60-150';  // Example user ID (should be dynamic based on logged-in user)

// Handle photo upload
if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == 0) {
    $photo_tmp = $_FILES['user_photo']['tmp_name'];
    $photo_name = $_FILES['user_photo']['name'];
    $photo_path = 'uploads/' . basename($photo_name);
    
    // Move the uploaded photo to the 'uploads' folder
    if (move_uploaded_file($photo_tmp, $photo_path)) {
        // Update the user's profile photo in the database
        $query = "UPDATE users SET user_photo = ?, tshirt_size = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $photo_path, $tshirt_size, $user_id);
        $stmt->execute();
        
        echo "Profile updated successfully!";
    } else {
        echo "Error uploading photo.";
    }
} else {
    // If no new photo is uploaded, just update the t-shirt size
    $query = "UPDATE users SET tshirt_size = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $tshirt_size, $user_id);
    $stmt->execute();
    
    echo "Profile updated successfully!";
}
?>

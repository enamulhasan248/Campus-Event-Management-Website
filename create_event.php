<?php
session_start();
$showAlert = false;
$showError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    // Sanitize user inputs to prevent SQL injection
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $category = mysqli_real_escape_string($conn, $_POST["category"]);
    $capacity = mysqli_real_escape_string($conn, $_POST["capacity"]);
    $status = mysqli_real_escape_string($conn, $_POST["status"]);
    $venue = mysqli_real_escape_string($conn, $_POST["venue"]);
    $event_date = mysqli_real_escape_string($conn, $_POST["event_date"]);  // <-- Added event date

    // Get the logged-in username
    $created_by = $_SESSION['username'];

    // Handle file upload
    $event_image = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];  // allowed file types
        $file_type = $_FILES['event_image']['type'];
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate a unique file name
            $filename = uniqid() . "-" . basename($_FILES['event_image']['name']);
            $target_file = $upload_dir . $filename;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['event_image']['tmp_name'], $target_file)) {
                $event_image = $conn->real_escape_string($target_file);  // Store file path in DB
            } else {
                $showError = "Error uploading file.";
                exit;
            }
        } else {
            $showError = "Invalid file type. Only JPG, PNG, GIF allowed.";
            exit;
        }
    }

    // Insert event data into the database including eventdate
    $sql_insert = "INSERT INTO events (title, description, category, capacity, status, venue, event_image, created_by, eventdate) 
                   VALUES ('$title', '$description', '$category', '$capacity', '$status', '$venue', '$event_image', '$created_by', '$event_date')";

    if (mysqli_query($conn, $sql_insert)) {
        $showAlert = true;  // Event created successfully
    } else {
        $showError = "Database insertion error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Event</title>
  <link rel="stylesheet" href="CreateEventFormStyles.css" />

  

</head>
<body>
  

  <div class="page-header">
    <h1>Create an amazing event</h1>
    <p>Fill in the details below to create an event</p>
  </div>

  <div class="form-box">
    <form method="POST" action="create_event.php" enctype="multipart/form-data">
      <label for="title">Event Title</label>
      <input type="text" id="title" name="title" required />

      <label for="description">Description</label>
      <textarea id="description" name="description" rows="2" required></textarea>

      <label for="category">Category</label>
      <select id="category" name="category" required>
        <option value="">Select Category</option>
        <option value="Tech">Tech</option>
        <option value="Cultural">Cultural</option>
        <option value="Sports">Sports</option>
        <option value="Academic">Academic</option>
      </select>

      <div class="row">
        <div class="half">
          <label for="capacity">Capacity</label>
          <input type="number" id="capacity" name="capacity" min="1" required />
        </div>
        <div class="half">
          <label for="status">Status</label>
          <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="later">For later</option>
          </select>
        </div>
      </div>

      <label for="event_date">Event Date</label>
      <input type="date" id="event_date" name="event_date" required />

      <label for="venue">Venue Name</label>
      <input type="text" id="venue" name="venue" required />

      <label for="event_image">Event Image</label>
      <input type="file" id="event_image" name="event_image" accept="image/*" />

      <?php 
        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Event Created.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';

            // Redirect after 1 second
            echo '<script>
                    setTimeout(function() {
                        window.location.href = "admin.php";
                    }, 1000); // 1000ms = 1 second
                  </script>';
        }
        if (!empty($showError)) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ' . htmlspecialchars($showError) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        }
        ?>



      <button type="submit">Create Event</button>
    </form>
  </div>

  <!-- Include jQuery and Bootstrap JS for alerts (optional) -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>

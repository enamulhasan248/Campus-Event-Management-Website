<?php
session_start();
include 'partials/_dbconnect.php';  // your DB connection file

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Validate event_id in URL
if (!isset($_GET['event_id'])) {
    echo "Event not specified.";
    exit();
}

$event_id = intval($_GET['event_id']);

// Fetch event details from DB
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Event not found.";
    exit();
}

$event = $result->fetch_assoc();

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $username = $_SESSION['username']; // Ensure username is stored in session
    $event_name = $event['title'];

    // Check if user already registered this event
    $check_sql = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // User already registered
        $error_message = "You have already registered for this event.";
    } else {
        // Proceed to insert new registration
        $insert_sql = "INSERT INTO registrations (user_id, username, event_id, event_name) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssis", $user_id, $username, $event_id, $event_name);

        if ($insert_stmt->execute()) {
            $success_message = "You have successfully registered for the event!";
        } else {
            $error_message = "Error registering: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register for Event</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  body {
    background-color: #f0f0f0;
    padding: 20px;
  }
  .event-image {
    max-width: 100%; /* Ensures the image is responsive */
    height: 400px;
    border-radius: 12px;
    display: block; /* Make the image block-level */
    margin-left: auto;  /* Center the image horizontally */
    margin-right: auto; /* Center the image horizontally */
  }
  .container {
    max-width: 700px;
    background: white;
    padding: 20px 30px;
    border-radius: 15px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
  }
</style>
</head>
<body>

<div class="container">
  <h1>Register for Event: <?php echo htmlspecialchars($event['title']); ?></h1>
  <img src="<?php echo htmlspecialchars($event['event_image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image mb-4" />

  <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
  <p><strong>Event Date:</strong> <?php echo !empty($event['eventdate']) ? date("F j, Y", strtotime($event['eventdate'])) : "N/A"; ?></p>

  <?php if ($success_message): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
  <?php endif; ?>

  <?php if ($error_message): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
  <?php endif; ?>

  <?php if (!$success_message): ?>
  <form method="POST" action="">
    <button type="submit" class="btn btn-primary w-100">Confirm Registration</button>
  </form>
  <?php endif; ?>

  <a href="normal_user.php" class="btn btn-secondary mt-3">Back to Events</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

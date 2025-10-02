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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $eventdate = $_POST['eventdate'];
    $venue = $_POST['venue'];

    // Handle image upload (optional)
    if ($_FILES['event_image']['error'] === 0) {
        $image_tmp = $_FILES['event_image']['tmp_name'];
        $image_name = $_FILES['event_image']['name'];
        $image_path = "uploads/" . basename($image_name);
        move_uploaded_file($image_tmp, $image_path);
    } else {
        // Use the existing image if not uploading a new one
        $image_path = $event['event_image'];
    }

    // Update event in database
    $update_sql = "UPDATE events SET title = ?, description = ?, eventdate = ?, venue = ?, event_image = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sssssi", $title, $description, $eventdate, $venue, $image_path, $event_id);

    if ($stmt_update->execute()) {
        $success_message = "Event updated successfully!";
    } else {
        $error_message = "Error updating event: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <style>
        /* Blueish Cool Design */
        body {
            background-color: #f0f8ff; /* Light blue background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 255, 0.1); /* Subtle shadow */
            max-width: 800px;
            margin-top: 50px;
        }

        h1 {
            color: #1e3a8a; /* Dark blue color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 1.1rem;
            color: #2d3748; /* Dark gray for labels */
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #b0c4de;
            box-shadow: 0 0 8px rgba(0, 0, 255, 0.2);
        }

        .form-control:focus {
            border-color: #61dafb;
            box-shadow: 0 0 10px rgba(97, 218, 251, 0.7);
        }

        .btn-primary {
            background-color: #1e3a8a;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2563eb; /* Lighter blue on hover */
        }

        .btn-secondary {
            background-color: #61dafb;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
        }

        .btn-secondary:hover {
            background-color: #0c7abf;
        }

        .alert {
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .event-image {
            max-width: 100%;
            height: 400px;
            border-radius: 12px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        /* Optional: add transitions to inputs and buttons */
        .form-control, .btn {
            transition: all 0.3s ease-in-out;
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(97, 218, 251, 0.7);
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h1>Edit Event: <?php echo htmlspecialchars($event['title']); ?></h1>

    <!-- Display success or error messages -->
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Event Edit Form -->
    <form method="POST" action="edit_event.php?event_id=<?php echo $event_id; ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Event Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="eventdate" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="eventdate" name="eventdate" value="<?php echo $event['eventdate']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="venue" class="form-label">Venue</label>
            <input type="text" class="form-control" id="venue" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="event_image" class="form-label">Event Image (optional)</label>
            <input type="file" class="form-control" id="event_image" name="event_image">
            <small>Leave this field empty to keep the current image.</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>

    <a href="admin.php" class="btn btn-secondary mt-3">Back to Events</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>

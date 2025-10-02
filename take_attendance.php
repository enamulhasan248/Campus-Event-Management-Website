<?php
session_start(); // Start the session

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'partials/_dbconnect.php';

// Check if event_id is provided
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch the event name and registered users for this event
    $sql = "SELECT e.title AS event_name, u.username, u.id AS user_id, a.attendance_status
            FROM users u
            LEFT JOIN registrations r ON u.id = r.user_id
            LEFT JOIN attendance a ON u.id = a.user_id AND r.event_id = a.event_id
            LEFT JOIN events e ON r.event_id = e.id
            WHERE r.event_id = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // Print the error if the prepare statement fails
        die('MySQL prepare error: ' . $conn->error);
    }
    
    // Bind the parameters
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $registered_users = $result->fetch_all(MYSQLI_ASSOC);
        $event_name = $registered_users[0]['event_name']; // Get event name from the first user row
    } else {
        $registered_users = [];
        $event_name = 'Event Not Found';
    }
} else {
    // Redirect if event_id is not provided
    header("Location: user_created_event.php");
    exit();
}

// Update attendance status when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status']) && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];
    $statuses = $_POST['status'];

    // Loop through each user and update attendance status
    foreach ($statuses as $user_id => $status) {
        // Check if the user already has an entry in the attendance table
        $check_sql = "SELECT * FROM attendance WHERE user_id = ? AND event_id = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("si", $user_id, $event_id);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();

        if ($check_result->num_rows > 0) {
            // If the attendance entry exists, update the status
            $sql = "UPDATE attendance SET attendance_status = ? WHERE user_id = ? AND event_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $status, $user_id, $event_id);
            $stmt->execute();
        } else {
            // If no entry exists, insert a new entry with the given attendance status
            $sql = "INSERT INTO attendance (event_id, user_id, attendance_status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $event_id, $user_id, $status);
            $stmt->execute();
        }
    }

    // Reload the page after update to show updated status
    header("Location: take_attendance.php?event_id=" . $event_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="take_attendance.css">
</head>
<body>
    <h1>Take Attendance for Event: <?= htmlspecialchars($event_name) ?></h1>
    <div class="container">
        <form action="take_attendance.php?event_id=<?= $event_id ?>" method="POST">
            <div class="user-list">
                <?php foreach ($registered_users as $user): ?>
                    <div class="user-item">
                        <p><strong><?= htmlspecialchars($user['username']) ?></strong></p>
                        <label for="status_<?= $user['user_id'] ?>">Attendance Status:</label>
                        <input type="radio" name="status[<?= $user['user_id'] ?>]" value="1" <?= $user['attendance_status'] == 1 ? 'checked' : '' ?>> Present
                        <input type="radio" name="status[<?= $user['user_id'] ?>]" value="0" <?= $user['attendance_status'] == 0 ? 'checked' : '' ?>> Absent
                        <button type="button" class="btn btn-danger remove-user-btn" data-user-id="<?= $user['user_id'] ?>">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="event_id" value="<?= $event_id ?>">
            <button type="submit" class="button">Update Attendance</button>
        </form>

        <!-- Back Button -->
        <a href="admin.php" class="back-button">Back to Created Events</a>
    </div>


     <!-- <script>
        document.querySelectorAll('.remove-user-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                const eventId = <?= json_encode($event_id) ?>;

                if (confirm('Are you sure you want to remove this user from the event?')) {
                    fetch('remove_user.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `user_id=${userId}&event_id=${eventId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User removed successfully.');
                            location.reload();
                        } else {
                            alert('Error removing user: ' + data.error);
                        }
                    });
                }
            });
        });
    </script> -->
</body>
</html>

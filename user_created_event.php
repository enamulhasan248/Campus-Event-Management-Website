<?php
session_start(); // Start the session

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'partials/_dbconnect.php';

// Fetch events created by the logged-in user
$created_by = $_SESSION['username'];
$sql = "SELECT * FROM events WHERE created_by = '$created_by'";
$result = mysqli_query($conn, $sql);

// Check if the query returned any events
if ($result && mysqli_num_rows($result) > 0) {
    $events = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $events = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Events - Take Attendance</title>
    
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .page-header {
            text-align: center;
            padding: 10px 0;
            margin-top: 90px;
            color: white;
            margin-bottom: 20px;
        }

        .page-header h1, p {
            font-size: 32px; /* Bigger size for emphasis */
            margin: 0;
            color: #01579b; /* Cool blue color */
            font-weight: 700; /* Bold font */
            text-transform: uppercase; /* All caps for extra emphasis */
            letter-spacing: 2px; /* Slightly spaced letters for a cooler effect */
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); /* Add a soft shadow for depth */
            }

        .page-header p {
            font-size: 16px;
        }

        .form-box {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* Styling the card container */
        .event-card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Card styling */
        .event-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }

        .event-card h3 {
            font-size: 22px;
            margin: 10px 0;
        }

        .event-card p {
            font-size: 14px;
            color: #666;
        }

        .event-card .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .event-card .btn:hover {
            background-color: #0055cc;
        }

        .no-events-message {
            text-align: center;
            font-size: 18px;
            color: #003366;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Your Created Events</h1>
        <p>Click "Take Attendance" for any event to mark attendance.</p>
    </div>

    <div class="form-box">
        <?php if (!empty($events)): ?>
            <div class="event-card-container">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p><strong>Category:</strong> <?= htmlspecialchars($event['category']) ?></p>
                        <p><strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?></p>
                        <a href="take_attendance.php?event_id=<?= $event['id'] ?>" class="btn">Take Attendance</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-events-message">No events found. Create an event first.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<!-- 
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

include 'partials/_dbconnect.php';

if (isset($_POST['user_id'], $_POST['event_id'])) {
    $user_id = intval($_POST['user_id']);
    $event_id = intval($_POST['event_id']);

 

    // Delete from registrations
    $stmt2 = $conn->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt2->bind_param("ii", $user_id, $event_id);
    $stmt2->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
} -->

<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['student_logged_in']) && !isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'User not logged in.']);
    exit();
}

$regno = $_SESSION['student_regno'] ?? $_SESSION['teacher_regno'];  // Get user's regno from session

// Get messages for the logged-in user
$sql = "SELECT * FROM messages WHERE receiver_regno = ? ORDER BY sent_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(['status' => 'true', 'message' => 'Messages fetched successfully!', 'data' => $messages]);
?>

<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['student_logged_in']) && !isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'User not logged in.']);
    exit();
}

// Get message ID from the request
$message_id = $_POST['message_id'];

// Update message status to "read"
$sql = "UPDATE messages SET status = 'read' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $message_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'true', 'message' => 'Message marked as read!']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Error marking message as read.']);
}
$stmt->close();
?>

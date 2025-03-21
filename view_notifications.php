<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['student_logged_in']) && !isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'User not logged in.']);
    exit();
}

// Get the notifications from the database
$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode(['status' => 'true', 'message' => 'Notifications fetched successfully!', 'data' => $notifications]);
?>

<?php
session_start();
include 'db.php'; // Database connection

// Get the enrollment ID and the action (accept/reject) from the URL
$enrollment_id = $_GET['id'];
$action = $_GET['action'];

// Validate the action
if (!in_array($action, ['accept', 'reject'])) {
    echo json_encode(['status' => 'false', 'message' => 'Invalid action.']);
    exit();
}

// Update the enrollment status based on teacher's action
$sql_update = "UPDATE student_courses SET status = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $action, $enrollment_id);

if ($stmt_update->execute()) {
    echo json_encode(['status' => 'true', 'message' => 'Enrollment status updated successfully.']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Error updating the enrollment status.']);
}

$stmt_update->close();
$conn->close();
?>

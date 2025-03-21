<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['student_logged_in']) && !isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'User not logged in.']);
    exit();
}

// Handle form submission for sending message
if (isset($_POST['submit'])) {
    $receiver_regno = $_POST['receiver_regno'];
    $message = $_POST['message'];
    $sender_regno = $_SESSION['student_regno'] ?? $_SESSION['teacher_regno']; // Get sender's regno from session

    // Insert the message into the database
    $sql = "INSERT INTO messages (sender_regno, receiver_regno, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $sender_regno, $receiver_regno, $message);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'true', 'message' => 'Message sent successfully!']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error sending message.']);
    }
    $stmt->close();
}
?>

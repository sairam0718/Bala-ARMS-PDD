<?php
session_start();
include 'db.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Admin not logged in.']);
    exit();
}

// Handle form submission for sending notification
if (isset($_POST['submit'])) {
    // Get the form data
    $title = $_POST['title'];
    $message = $_POST['message'];

    // Insert the notification into the database
    $sql = "INSERT INTO notifications (title, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $message);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'true', 'message' => 'Notification sent successfully!']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error sending notification.']);
    }
    $stmt->close();
}
?>

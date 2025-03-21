<?php
session_start();
include 'db.php'; // Include database connection

// Check if student is logged in
if (!isset($_SESSION['student_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Student not logged in.']);
    exit();
}

// Handle assignment submission
if (isset($_POST['submit']) && isset($_FILES['assignment_file'])) {
    $assignment_id = $_POST['assignment_id'];
    $regno = $_SESSION['student_regno'];  // Student's regno from session

    // Handle file upload
    $upload_dir = 'uploads/assignments/';
    $file_name = $_FILES['assignment_file']['name'];
    $file_path = $upload_dir . basename($file_name);

    if (move_uploaded_file($_FILES['assignment_file']['tmp_name'], $file_path)) {
        // Insert the assignment submission into the database
        $sql = "INSERT INTO assignment_submissions (assignment_id, regno, file_path, status) VALUES (?, ?, ?, 'submitted')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $assignment_id, $regno, $file_path);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'true', 'message' => 'Assignment submitted successfully!']);
        } else {
            echo json_encode(['status' => 'false', 'message' => 'Error submitting assignment.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error uploading the assignment file.']);
    }
}
?>

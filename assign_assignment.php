<?php
session_start();
include 'db.php'; // Include database connection

// Check if teacher is logged in
if (!isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Teacher not logged in.']);
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Get the form data
    $course_code = $_POST['course_code'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $teacher_regno = $_SESSION['teacher_regno'];  // Teacher's regno from session

    // Insert the assignment into the database
    $sql = "INSERT INTO assignments (course_code, title, description, due_date, teacher_regno) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $course_code, $title, $description, $due_date, $teacher_regno);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'true', 'message' => 'Assignment assigned successfully!']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error assigning assignment.']);
    }
    $stmt->close();
}
?>

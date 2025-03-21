<?php
session_start();
include 'db.php'; // Include database connection

// Check if teacher is logged in
if (!isset($_SESSION['teacher_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Teacher not logged in.']);
    exit();
}

// Handle file upload
if (isset($_POST['submit']) && isset($_FILES['course_material'])) {
    // Get the form data
    $course_code = $_POST['course_code'];
    $teacher_regno = $_SESSION['teacher_regno'];  // Teacher's regno from session

    // Handle file upload
    $upload_dir = 'uploads/course_materials/';
    $file_name = $_FILES['course_material']['name'];
    $file_path = $upload_dir . basename($file_name);

    if (move_uploaded_file($_FILES['course_material']['tmp_name'], $file_path)) {
        // Insert the course material into the database
        $sql = "INSERT INTO course_materials (course_code, file_name, file_path, teacher_regno) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $course_code, $file_name, $file_path, $teacher_regno);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'true', 'message' => 'Course material uploaded successfully!']);
        } else {
            echo json_encode(['status' => 'false', 'message' => 'Error uploading course material.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error uploading the file.']);
    }
}
?>

<?php
session_start();
include 'db.php'; // Include database connection

// Check if student is logged in
if (!isset($_SESSION['student_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Student not logged in.']);
    exit();
}

// Get the course code from the student
$course_code = $_GET['course_code']; // Assume course_code is passed as a query parameter

$sql = "SELECT * FROM assignments WHERE course_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $course_code);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode(['status' => 'true', 'message' => 'Assignments fetched successfully!', 'data' => $assignments]);
?>

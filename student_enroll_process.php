<?php
session_start();
include 'db.php'; // Database connection

// Get course details from URL
$course_code = $_GET['course_code'];
$slot = $_GET['slot'];
$regno = $_SESSION['regno']; // Assuming student's regno is stored in session

// Check if the student is already enrolled in this course
$sql_check_enrollment = "SELECT * FROM student_courses WHERE regno = ? AND course_code = ?";
$stmt_check = $conn->prepare($sql_check_enrollment);
$stmt_check->bind_param("ss", $regno, $course_code);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(['status' => 'false', 'message' => 'You are already enrolled in this course.']);
    exit();
}

// Check if the course has reached the maximum number of students
$sql_check_max = "SELECT COUNT(*) as enrolled_count FROM student_courses WHERE course_code = ?";
$stmt_check_max = $conn->prepare($sql_check_max);
$stmt_check_max->bind_param("s", $course_code);
$stmt_check_max->execute();
$result_check_max = $stmt_check_max->get_result();
$row_max = $result_check_max->fetch_assoc();

$sql_course_details = "SELECT * FROM courses WHERE course_code = ?";
$stmt_course_details = $conn->prepare($sql_course_details);
$stmt_course_details->bind_param("s", $course_code);
$stmt_course_details->execute();
$result_course_details = $stmt_course_details->get_result();
$row_course_details = $result_course_details->fetch_assoc();

if ($row_max['enrolled_count'] >= $row_course_details['max_students']) {
    echo json_encode(['status' => 'false', 'message' => 'Sorry, this course is full.']);
    exit();
}

// Enroll the student in the course if not already enrolled and space is available
$sql_enroll = "INSERT INTO student_courses (regno, course_code, slot) VALUES (?, ?, ?)";
$stmt_enroll = $conn->prepare($sql_enroll);
$stmt_enroll->bind_param("sss", $regno, $course_code, $slot);

if ($stmt_enroll->execute()) {
    echo json_encode(['status' => 'true', 'message' => 'You have successfully enrolled in the course.']);
} else {
    echo json_encode(['status' => 'false', 'message' => 'Error enrolling in the course.']);
}

$stmt_enroll->close();
$stmt_check->close();
$stmt_check_max->close();
$conn->close();
?>

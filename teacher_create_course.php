<?php
session_start();
include 'db.php'; // Database connection

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $slot = $_POST['slot'];
    $max_students = $_POST['max_students'];

    // Validate input
    if (empty($course_code) || empty($course_name) || empty($slot) || empty($max_students)) {
        echo json_encode(['status' => 'false', 'message' => 'All fields are required.']);
        exit();
    }

    // Insert course into the database
    $sql = "INSERT INTO courses (course_code, course_name, slot, max_students) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $course_code, $course_name, $slot, $max_students);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'true', 'message' => 'Course created successfully.']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error creating course.']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Course</title>
</head>
<body>
    <h2>Create New Course</h2>
    <form method="POST" action="">
        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code" required><br><br>

        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br><br>

        <label for="slot">Select Slot:</label>
        <select id="slot" name="slot" required>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select><br><br>

        <label for="max_students">Max Students:</label>
        <input type="number" id="max_students" name="max_students" required><br><br>

        <button type="submit">Create Course</button>
    </form>
</body>
</html>

<?php
session_start();
include 'db.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $slot = $_POST['slot'];

    // Validate input
    if (empty($course_code) || empty($course_name) || empty($slot)) {
        echo json_encode(['status' => 'false', 'message' => 'All fields are required.']);
        exit();
    }

    // Insert survey data into the database
    $sql = "INSERT INTO course_survey (course_code, course_name, slot) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $course_code, $course_name, $slot);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'true', 'message' => 'Survey submitted successfully.']);
    } else {
        echo json_encode(['status' => 'false', 'message' => 'Error submitting survey.']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Survey</title>
</head>
<body>
    <h2>Course Enrollment Survey</h2>
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

        <button type="submit">Submit Survey</button>
    </form>
</body>
</html>

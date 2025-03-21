<?php
session_start();
include 'db.php'; // Database connection

// Fetch available courses that the student can enroll in
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Available Courses for Enrollment</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Course Name: " . $row['course_name'] . "<br>";
        echo "Course Code: " . $row['course_code'] . "<br>";
        echo "Slot: " . $row['slot'] . "<br>";
        echo "Max Students: " . $row['max_students'] . "<br>";
        echo "<a href='student_enroll_process.php?course_code=" . $row['course_code'] . "&slot=" . $row['slot'] . "'>Enroll</a><br><br>";
    }
} else {
    echo "No courses available for enrollment.";
}
?>

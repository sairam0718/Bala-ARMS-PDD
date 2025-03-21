<?php
session_start();
include 'db.php'; // Database connection

$regno = $_SESSION['regno']; // Assuming student's regno is stored in the session

// Fetch the student's course enrollments and statuses
$sql = "SELECT sc.course_code, c.course_name, sc.slot, sc.status 
        FROM student_courses sc
        JOIN courses c ON sc.course_code = c.course_code
        WHERE sc.regno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Your Enrolled Courses</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Course: " . $row['course_name'] . " (" . $row['course_code'] . ")<br>";
        echo "Slot: " . $row['slot'] . "<br>";
        echo "Status: " . $row['status'] . "<br><br>";
    }
} else {
    echo "You are not enrolled in any courses.";
}
?>

<?php
session_start();
include 'db.php';

// Fetch survey results grouped by course and slot
$sql = "SELECT course_code, course_name, slot, COUNT(*) as student_count 
        FROM course_survey 
        GROUP BY course_code, course_name, slot";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Course Survey Results</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Course: " . $row['course_name'] . " (Code: " . $row['course_code'] . ")<br>";
        echo "Slot: " . $row['slot'] . "<br>";
        echo "Students Requested: " . $row['student_count'] . "<br><br>";
    }
} else {
    echo "No survey data found.";
}
?>

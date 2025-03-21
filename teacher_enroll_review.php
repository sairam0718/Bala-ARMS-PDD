<?php
session_start();
include 'db.php'; // Database connection

// Assuming the teacher is logged in, and we have their teacher ID in the session
$teacher_id = $_SESSION['teacher_id'];

// Fetch the list of students who have enrolled in the teacher's courses
$sql = "SELECT sc.id, sc.regno, sc.course_code, sc.slot, sc.status, c.course_name 
        FROM student_courses sc 
        JOIN courses c ON sc.course_code = c.course_code
        WHERE c.teacher_id = ?"; // Assuming the courses table has teacher_id
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h2>Pending Student Enrollments</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "Student RegNo: " . $row['regno'] . "<br>";
        echo "Course: " . $row['course_name'] . " (" . $row['course_code'] . ")<br>";
        echo "Slot: " . $row['slot'] . "<br>";
        echo "Status: " . $row['status'] . "<br>";
        
        if ($row['status'] == 'pending') {
            echo "<a href='teacher_accept_reject.php?id=" . $row['id'] . "&action=accept'>Accept</a> | 
                  <a href='teacher_accept_reject.php?id=" . $row['id'] . "&action=reject'>Reject</a><br><br>";
        } else {
            echo "<hr>";
        }
    }
} else {
    echo "No pending student enrollments.";
}
?>

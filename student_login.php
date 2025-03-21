<?php
include 'db.php';  // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_regno = $_POST['regno'] ?? '';
    $student_password = $_POST['password'] ?? '';

    // Check if registration number and password are provided
    if (empty($student_regno) || empty($student_password)) {
        echo json_encode(['status' => false, 'message' => 'Please enter both registration number and password.']);
        exit();
    }

    // Check if the student exists in the database
    $sql_check = "SELECT * FROM students WHERE regno = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $student_regno);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo json_encode(['status' => false, 'message' => 'Student not found.']);
    } else {
        $student = $result_check->fetch_assoc();

        // Verify the password
        if (password_verify($student_password, $student['password'])) {
            // Redirect to student dashboard
            header('Location: student_dashboard.php');
            exit(); // Ensure the script stops after the redirection
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid password.']);
        }
    }

    // Close the prepared statement and database connection
    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
</head>
<body>
    <h2>Student Login</h2>
    <form method="POST" action="student_login.php">
        <label for="regno">Registration Number:</label>
        <input type="text" id="regno" name="regno" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php
include 'db.php';  // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_regno = $_POST['regno'] ?? '';
    $teacher_password = $_POST['password'] ?? '';

    // Check if registration number and password are provided
    if (empty($teacher_regno) || empty($teacher_password)) {
        echo json_encode(['status' => false, 'message' => 'Please enter both registration number and password.']);
        exit();
    }

    // Check if the teacher exists in the database
    $sql_check = "SELECT * FROM teachers WHERE regno = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $teacher_regno);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo json_encode(['status' => false, 'message' => 'Teacher not found.']);
    } else {
        $teacher = $result_check->fetch_assoc();

        // Verify the password
        if (password_verify($teacher_password, $teacher['password'])) {
            // Redirect to teacher dashboard
            header('Location: teacher_dashboard.php');
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
    <title>Teacher Login</title>
</head>
<body>
    <h2>Teacher Login</h2>
    <form method="POST" action="teacher_login.php">
        <label for="regno">Registration Number:</label>
        <input type="text" id="regno" name="regno" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>

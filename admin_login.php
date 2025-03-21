<?php
include 'db.php';  // Include database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_username = $_POST['username'] ?? '';
    $admin_password = $_POST['password'] ?? '';

    // Check if username and password are provided
    if (empty($admin_username) || empty($admin_password)) {
        echo json_encode(['status' => false, 'message' => 'Please enter both username and password.']);
        exit();
    }

    // Check if the admin exists in the database
    $sql_check = "SELECT * FROM admin WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $admin_username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo json_encode(['status' => false, 'message' => 'Admin not found.']);
    } else {
        $admin = $result_check->fetch_assoc();

        // Verify the password
        if (password_verify($admin_password, $admin['password'])) {
            // Redirect to admin dashboard
            header('Location: admin_dashboard.php');
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
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="POST" action="admin_login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>

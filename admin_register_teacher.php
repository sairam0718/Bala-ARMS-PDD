<?php
include 'db.php'; // Include database connection

$response = array(); // Initialize response array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $regno = $_POST['regno'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert into the teachers table
    $sql = "INSERT INTO teachers (regno, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $regno, $password);

    if ($stmt->execute()) {
        // Success response
        $response['status'] = true;
        $response['message'] = "Teacher registered successfully!";
    } else {
        // Error response
        $response['status'] = false;
        $response['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Invalid request method
    $response['status'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();

// Return the response as JSON
echo json_encode($response);
?>

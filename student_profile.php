<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['regno'])) {
    die("Error: Student is not logged in. Please log in first.");
}

$regno = $_SESSION['regno'];

// Prepare and execute SQL query securely
$sql = "SELECT * FROM student_profiles WHERE regno = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Close the statement
$stmt->close();
$conn->close();

// Display profile data
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .profile-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        img {
            max-width: 150px;
            height: auto;
            border-radius: 50%;
        }
        h1 {
            color: #4CAF50;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h1>Student Profile</h1>

    <?php if ($profile): ?>
        <p><strong>Name:</strong> <?= htmlspecialchars($profile['full_name']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($profile['dob']) ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($profile['contact_number']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($profile['address']) ?></p>
        <p><strong>Bio:</strong> <?= htmlspecialchars($profile['bio']) ?></p>
        <?php if (!empty($profile['profile_picture'])): ?>
            <p><img src="<?= htmlspecialchars($profile['profile_picture']) ?>" alt="Profile Picture"></p>
        <?php endif; ?>
    <?php else: ?>
        <p>No profile found.</p>
    <?php endif; ?>

</div>

</body>
</html>

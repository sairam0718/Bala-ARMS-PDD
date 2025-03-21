<?php
session_start();
include 'db.php'; // Include database connection

// Get the logged-in teacher's regno (assuming session stores regno)
$regno = $_SESSION['regno'];

// Fetch teacher profile details from the database
$sql = "SELECT * FROM teacher_profiles WHERE regno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Display profile
if ($profile) {
    echo "<h1>Teacher Profile</h1>";
    echo "<p>Name: " . $profile['full_name'] . "</p>";
    echo "<p>Date of Birth: " . $profile['dob'] . "</p>";
    echo "<p>Contact Number: " . $profile['contact_number'] . "</p>";
    echo "<p>Email: " . $profile['email'] . "</p>";
    echo "<p>Address: " . $profile['address'] . "</p>";
    echo "<p>Bio: " . $profile['bio'] . "</p>";
    if ($profile['profile_picture']) {
        echo "<img src='" . $profile['profile_picture'] . "' alt='Profile Picture'>";
    }
} else {
    echo "<p>No profile found.</p>";
}

$stmt->close();
$conn->close();
?>

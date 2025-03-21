<?php
session_start();
include 'db.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    echo json_encode(['status' => 'false', 'message' => 'Admin not logged in.']);
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Get the form data
    $regno = $_POST['regno'];
    $full_name = $_POST['full_name'];
    $dob = $_POST['dob'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];

    // Handle profile picture upload
    $profile_picture = "";
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_pictures/';
        $uploaded_file = $upload_dir . basename($_FILES['profile_picture']['name']);
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploaded_file)) {
            $profile_picture = $uploaded_file; // Save the file path
        }
    }

    // Determine if we are creating a student or teacher profile
    $role = $_POST['role'];

    // Insert or Update profile based on the role
    if ($role === 'student') {
        // Check if the student profile already exists
        $sql_check = "SELECT * FROM student_profiles WHERE regno = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $regno);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Update the student profile
            $sql_update = "UPDATE student_profiles SET full_name = ?, dob = ?, contact_number = ?, email = ?, address = ?, profile_picture = ?, bio = ? WHERE regno = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssssss", $full_name, $dob, $contact_number, $email, $address, $profile_picture, $bio, $regno);
            if ($stmt_update->execute()) {
                echo json_encode(['status' => 'true', 'message' => 'Student profile updated successfully!']);
            } else {
                echo json_encode(['status' => 'false', 'message' => 'Error updating student profile.']);
            }
            $stmt_update->close();
        } else {
            // Insert new student profile
            $sql_insert = "INSERT INTO student_profiles (regno, full_name, dob, contact_number, email, address, profile_picture, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssssss", $regno, $full_name, $dob, $contact_number, $email, $address, $profile_picture, $bio);
            if ($stmt_insert->execute()) {
                echo json_encode(['status' => 'true', 'message' => 'Student profile created successfully!']);
            } else {
                echo json_encode(['status' => 'false', 'message' => 'Error creating student profile.']);
            }
            $stmt_insert->close();
        }

    } else if ($role === 'teacher') {
        // Check if the teacher profile already exists
        $sql_check = "SELECT * FROM teacher_profiles WHERE regno = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $regno);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Update the teacher profile
            $sql_update = "UPDATE teacher_profiles SET full_name = ?, dob = ?, contact_number = ?, email = ?, address = ?, profile_picture = ?, bio = ? WHERE regno = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssssss", $full_name, $dob, $contact_number, $email, $address, $profile_picture, $bio, $regno);
            if ($stmt_update->execute()) {
                echo json_encode(['status' => 'true', 'message' => 'Teacher profile updated successfully!']);
            } else {
                echo json_encode(['status' => 'false', 'message' => 'Error updating teacher profile.']);
            }
            $stmt_update->close();
        } else {
            // Insert new teacher profile
            $sql_insert = "INSERT INTO teacher_profiles (regno, full_name, dob, contact_number, email, address, profile_picture, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssssss", $regno, $full_name, $dob, $contact_number, $email, $address, $profile_picture, $bio);
            if ($stmt_insert->execute()) {
                echo json_encode(['status' => 'true', 'message' => 'Teacher profile created successfully!']);
            } else {
                echo json_encode(['status' => 'false', 'message' => 'Error creating teacher profile.']);
            }
            $stmt_insert->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create/Update Profile</title>
</head>
<body>
    <h1>Create or Update Profile</h1>
    <form action="admin_create_profile.php" method="post" enctype="multipart/form-data">
        <label for="regno">Regno:</label>
        <input type="text" name="regno" required><br><br>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select><br><br>

        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" required><br><br>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" required><br><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>

        <label for="address">Address:</label>
        <textarea name="address"></textarea><br><br>

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*"><br><br>

        <label for="bio">Bio:</label>
        <textarea name="bio"></textarea><br><br>

        <button type="submit" name="submit">Create/Update Profile</button>
    </form>
</body>
</html>

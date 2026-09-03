<?php
session_start();
include("connect.php");

if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['student_email'];

// Fetch student details
$query = "SELECT * FROM students WHERE email='$email'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Handle profile photo upload
    $updatePhoto = "";
    if (!empty($_FILES["profile_photo"]["name"])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["profile_photo"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFilePath)) {
                $updatePhoto = ", profile_photo='$targetFilePath'";
            } else {
                echo "<script>alert('Error uploading profile photo.');</script>";
            }
        } else {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        }
    }

    // Update database
    $updateQuery = "UPDATE students SET firstName='$firstName', lastName='$lastName', phone='$phone' $updatePhoto WHERE email='$email'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='student_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}

// Handle profile photo removal
if (isset($_POST['remove_photo'])) {
    $removeQuery = "UPDATE students SET profile_photo=NULL WHERE email='$email'";
    if (mysqli_query($conn, $removeQuery)) {
        echo "<script>alert('Profile photo removed successfully!'); window.location.href='student_profile.php';</script>";
    } else {
        echo "<script>alert('Error removing profile photo.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="style_profile.css">
   
</head>
<body>

    <h2>Student Profile</h2>

    <div class="profile-container">
        <!-- Display profile photo -->
        <img src="<?php echo $student['profile_photo'] ? $student['profile_photo'] : 'default_profile.png'; ?>" alt="Profile Photo">

        <form method="POST" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="firstName" value="<?php echo $student['firstName']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="lastName" value="<?php echo $student['lastName']; ?>" required>

            <label>Email:</label>
            <input type="email" value="<?php echo $student['email']; ?>" disabled>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $student['phone']; ?>" required>

            <label>Upload Profile Photo:</label>
            <input type="file" name="profile_photo" accept="image/*">

            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <!-- Remove profile photo option -->
        <?php if (!empty($student['profile_photo'])) { ?>
            <form method="POST">
                <button type="submit" name="remove_photo" class="remove-btn">Remove Profile Photo</button>
            </form>
        <?php } ?>

        <a href="student_homepage.php" class="back-link">⬅ Back to Homepage</a>
    </div>

</body>
</html>

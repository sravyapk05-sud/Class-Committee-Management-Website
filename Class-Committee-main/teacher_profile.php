<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['teacher_email'];

// Fetch teacher details
$query = "SELECT * FROM teachers WHERE email='$email'";
$result = mysqli_query($conn, $query);
$teacher = mysqli_fetch_assoc($result);

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
    $updateQuery = "UPDATE teachers SET firstName='$firstName', lastName='$lastName', phone='$phone' $updatePhoto WHERE email='$email'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='teacher_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}

// Handle profile photo removal
if (isset($_POST['remove_photo'])) {
    $removeQuery = "UPDATE teachers SET profile_photo=NULL WHERE email='$email'";
    if (mysqli_query($conn, $removeQuery)) {
        echo "<script>alert('Profile photo removed successfully!'); window.location.href='teacher_profile.php';</script>";
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
    <title>teacher Profile</title>
    <link rel="stylesheet" href="style_profile.css">
    
</head>
<body>

    <h2>teacher Profile</h2>

    <div class="profile-container">
        <!-- Display profile photo -->
        <img src="<?php echo $teacher['profile_photo'] ? $teacher['profile_photo'] : 'default_profile.png'; ?>" alt="Profile Photo">

        <form method="POST" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="firstName" value="<?php echo $teacher['firstName']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="lastName" value="<?php echo $teacher['lastName']; ?>" required>

            <label>Email:</label>
            <input type="email" value="<?php echo $teacher['email']; ?>" disabled>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?php echo $teacher['phone']; ?>" required>

            <label>Upload Profile Photo:</label>
            <input type="file" name="profile_photo" accept="image/*">

            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <!-- Remove profile photo option -->
        <?php if (!empty($teacher['profile_photo'])) { ?>
            <form method="POST">
                <button type="submit" name="remove_photo" class="remove-btn">Remove Profile Photo</button>
            </form>
        <?php } ?>

        <a href="teacher_homepage.php" class="back-link">⬅ Back to Homepage</a>
    </div>

</body>
</html>

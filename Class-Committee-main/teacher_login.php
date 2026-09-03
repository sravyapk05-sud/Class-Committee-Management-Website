<?php
session_start();
include("connect.php");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM teachers WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc(); // Fetch the data

        // Set session variables
        $_SESSION['teacher_email'] = $email;
        $_SESSION['role'] = 'teacher'; // Set role in session

        // Redirect to student homepage
        header("Location: teacher_homepage.php");
        exit(); // Ensure script stops after redirection
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <div class="container">
        <h2>Teacher Login</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
        <p>Don't have an account? <a href="teacher_register.php">Sign Up</a></p>
    </div>
</body>
</html>

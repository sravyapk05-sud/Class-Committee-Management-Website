<?php
session_start();

include("connect.php");

if(!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
} 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Homepage</title>
    <link rel="stylesheet" href="style_homepage.css">
</head>
<body>

     <!-- Navigation Bar -->
     <nav class="navbar">
        <div class="logo">Class Committee</div>
        <input type="text" class="search-bar" placeholder="Search...">
        <div class="nav-links">
            <a href="teacher_profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- Categories Section -->
    <div class="categories">
        <div class="category" onclick="window.location.href='teacher_complaints.php'">📌 Complaints</div>
        <div class="category" onclick="window.location.href='teacher_atten.php'">📊 Attendance </div>
        <div class="category" onclick="window.location.href='teacher_timetable.php'">📅 Timetable</div>
        <div class="category" onclick="window.location.href='teacher_event.php'">📅 Events</div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="banner">
            <h2>Welcome to the Class Committee Portal</h2>
            <p>Stay updated with the latest announcements and events.</p>
        </div>

        <div class="sections">
            <div class="card"onclick="window.location.href='teacher_survey.php'">📈 Survey</div>
            <!--<div class="card">📆 Upcoming Events</div>
            <div class="card">📩 Feedbacks</div>-->
        </div>
    </div>

    <div style="text-align:center; padding:15%;">
      <p  style="font-size:50px; font-weight:bold;">
       
        <?php 
       if(isset($_SESSION['teacher_email'])){
        $email = $_SESSION['teacher_email']; // The session variable storing the teacher's email
        // Query the database to get the teacher's information based on the email
        $query = mysqli_query($conn, "SELECT firstName, lastName FROM teachers WHERE email='$email'");
    
        // Check if a row was returned
        if($row = mysqli_fetch_array($query)){
            // Display the student's first and last name
            echo "Welcome, " . $row['firstName'] . " " . $row['lastName'];
        } else {
            echo "No user found.";
        }
    } else {
        // Redirect the user to the login page if they're not logged in
        header("Location: student_login.php");
        exit();
    }
    ?>

    <h1></h1>
    
</body>
</html>
<?php
session_start();
include("connect.php");

if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

// Fetch subjects dynamically from the database
$subjects = mysqli_query($conn, "SELECT * FROM subjects");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_email = $_SESSION['student_email'];
    $subject_id = $_POST['subject'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

    $query = "INSERT INTO survey (student_email, subject_id, feedback) VALUES ('$student_email', '$subject_id', '$feedback')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('✅ Feedback submitted successfully!');</script>";
    } else {
        echo "<script>alert('❌ Error submitting feedback!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📢 Student Feedback</title>
    <style>
        /* 🌟 General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            background: white;
            color: #0072ff;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* 🌟 Form Container */
        .container {
            width: 90%;
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* 🌟 Form Elements */
        label {
            font-size: 18px;
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            font-size: 16px;
            border-radius: 6px;
            border: 2px solid #ddd;
            outline: none;
            transition: 0.3s;
        }

        select:focus, textarea:focus {
            border-color: #0072ff;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        /* 🌟 Submit Button */
        .submit-btn {
            width: 100%;
            background: #0072ff;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #0056b3;
        }

        /* 🌟 Back Button */
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 6px;
            background: #ff4c4c;
            color: white;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #c93030;
        }
    </style>
</head>
<body>

    <h2>📢 Give Your Feedback</h2>

    <div class="container">
        <form method="POST">
            <label>📘 Select Subject:</label>
            <select name="subject" required>
                <option value="">-- Select Subject --</option>
                <?php while ($row = mysqli_fetch_assoc($subjects)) { ?>
                    <option value="<?= $row['id']; ?>"><?= $row['subject_name']; ?></option>
                <?php } ?>
            </select>

            <label>✍️ Your Feedback:</label>
            <textarea name="feedback" required placeholder="Write your feedback here..."></textarea>

            <button type="submit" class="submit-btn">📩 Submit Feedback</button>
        </form>

        <a href="student_homepage.php" class="back-btn">⬅ Back</a>
    </div>

</body>
</html>

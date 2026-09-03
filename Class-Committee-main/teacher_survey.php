<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch feedback with student full name and subjects
$query = "SELECT survey.id, CONCAT(students.firstName, ' ', students.lastName) AS student_name, 
                 subjects.subject_name, survey.feedback, survey.created_at 
          FROM survey
          JOIN subjects ON survey.subject_id = subjects.id
          JOIN students ON survey.student_email = students.email
          ORDER BY survey.created_at DESC";

$feedbacks = mysqli_query($conn, $query);
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
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #fff;
            padding: 15px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* 🌟 Table Styling */
        .table-container {
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #007bff;
            color: white;
            position: sticky;
            top: 0;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f1f1f1;
            transition: 0.3s;
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

        /* 🌟 Responsive Table */
        @media (max-width: 768px) {
            th, td {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>

    <h2>📢 Student Feedback</h2>

    <div class="table-container">
        <table>
            <tr>
                <th>👤 Student Name</th>
                <th>📘 Subject</th>
                <th>📝 Feedback</th>
                <th>📅 Submitted At</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($feedbacks)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']); ?></td>
                    <td><?= htmlspecialchars($row['subject_name']); ?></td>
                    <td><?= htmlspecialchars($row['feedback']); ?></td>
                    <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <a href="teacher_homepage.php" class="back-btn">⬅ Back</a>

</body>
</html>

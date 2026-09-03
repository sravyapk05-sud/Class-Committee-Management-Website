<?php
session_start();
include("connect.php");

if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['student_email'];
$attendanceQuery = $conn->query("SELECT * FROM attendance WHERE student_email='$email' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Homepage</title>

    <style>
        /* General Styling */
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: white;
            text-align: center;
            padding: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            text-align: center;
            color: white;
        }

        th {
            background: rgba(255, 255, 255, 0.3);
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        /* Back Button */
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            color: #4facfe;
            background: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: #4facfe;
            color: white;
            transform: scale(1.05);
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            table {
                width: 95%;
            }
            
            th, td {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Your Attendance</h2>

    <table>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Teacher</th>
        </tr>
        <?php while ($row = $attendanceQuery->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['teacher_email']; ?></td> <!-- Display the teacher's email -->
            </tr>
        <?php } ?>
    </table>

    <a href="student_homepage.php" class="back-btn">Go Back</a>
</body>
</html>

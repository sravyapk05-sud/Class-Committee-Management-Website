<?php
session_start();
include("connect.php");

// Check if user is logged in as student
if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

// Fetch timetable from database
$timetableQuery = mysqli_query($conn, "SELECT * FROM timetable ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📅 Class Timetable</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* 🌟 General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        h2 {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* 🌟 Table Styling */
        .container {
            width: 90%;
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
        }

        th {
            background: #0072ff;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:nth-child(odd) {
            background: #e8f0ff;
        }

        td {
            font-weight: bold;
            color: #333;
        }

        td:hover {
            background: #0072ff;
            color: white;
            transition: 0.3s ease-in-out;
        }

        /* Special Timetable Styling */
        .special-timetable {
            font-weight: bold;
            color: red;
            text-align: center;
        }

        /* 🌟 Buttons */
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .back-btn {
            background: #ff4c4c;
            color: white;
        }

        .back-btn:hover {
            background: #c93030;
        }
    </style>
</head>
<body>

    <h2>📅 Class Timetable</h2>

    <div class="container">
        <table>
            <tr>
                <th>Day</th>
                <th>Hour 1</th>
                <th>Hour 2</th>
                <th>Hour 3</th>
                <th>Hour 4</th>
                <th>Hour 5</th>
                <th>Hour 6</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($timetableQuery)) { ?>
                <?php 
                    // Check if all hours for Saturday are empty
                    if ($row['day'] === "Saturday" && empty($row['hour1']) && empty($row['hour2']) && empty($row['hour3']) && empty($row['hour4']) && empty($row['hour5']) && empty($row['hour6'])) { 
                ?>
                    <tr>
                        <td>Saturday</td>
                        <td colspan="6" class="special-timetable">Special Timetable</td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><?php echo $row['day']; ?></td>
                        <td><?php echo $row['hour1']; ?></td>
                        <td><?php echo $row['hour2']; ?></td>
                        <td><?php echo $row['hour3']; ?></td>
                        <td><?php echo $row['hour4']; ?></td>
                        <td><?php echo $row['hour5']; ?></td>
                        <td><?php echo $row['hour6']; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </table>

        <a href="student_homepage.php" class="btn back-btn">⬅ Back to Homepage</a>
    </div>

</body>
</html>

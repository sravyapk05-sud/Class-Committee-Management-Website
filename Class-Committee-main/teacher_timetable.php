<?php
session_start();
include("connect.php");

// Ensure the user is logged in as a teacher
if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch timetable data
$timetable = mysqli_query($conn, "SELECT * FROM timetable");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 15px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        .special-timetable {
            font-weight: bold;
            color: red;
            text-align: center;
        }
        .subject {
            font-size: 16px;
            font-weight: bold;
        }
        .teacher {
            font-size: 14px;
            color: gray;
        }
        .edit-btn {
            margin-top: 15px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .edit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <h2>Class Timetable</h2>

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
        
        <?php while ($row = mysqli_fetch_assoc($timetable)) { ?>
            <?php if ($row['day'] === "Saturday" && empty($row['hour1']) && empty($row['hour2']) && empty($row['hour3']) && empty($row['hour4']) && empty($row['hour5']) && empty($row['hour6'])) { ?>
                <tr>
                    <td>Saturday</td>
                    <td colspan="6" class="special-timetable">Special Timetable</td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td><?php echo $row['day']; ?></td>
                    
                    <?php for ($i = 1; $i <= 6; $i++) { 
                        $hour = "hour" . $i;
                        $subject_teacher = explode(" - ", $row[$hour]);
                        $subject = $subject_teacher[0] ?? "";
                        $teacher = $subject_teacher[1] ?? "";

                        echo "<td>
                                <div class='subject'>$subject</div>
                                <div class='teacher'>$teacher</div>
                              </td>";
                    } ?>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

    <a href="update_timetable.php" class="edit-btn">Modify Timetable</a><br>
    <a href="teacher_homepage.php" class="edit-btn">Back</a>


</body>
</html>



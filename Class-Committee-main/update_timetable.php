<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch subjects and teachers from the database
$subjects_query = mysqli_query($conn, "SELECT * FROM subjects");

// Fetch timetable data
$timetable_query = mysqli_query($conn, "SELECT * FROM timetable ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')");
$timetable = [];
while ($row = mysqli_fetch_assoc($timetable_query)) {
    $timetable[$row['day']] = $row;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['timetable'] as $day => $hours) {
        for ($i = 1; $i <= 6; $i++) {
            $subject_teacher = mysqli_real_escape_string($conn, $hours["hour$i"]);
            mysqli_query($conn, "UPDATE timetable SET hour$i = '$subject_teacher' WHERE day = '$day'");
        }
    }
    echo "<script>alert('Timetable updated successfully!'); window.location.href='teacher_timetable.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Timetable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        select {
            width: 100%;
            padding: 8px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <h2>Update Timetable</h2>

    <form method="POST">
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
            <?php foreach ($timetable as $day => $hours) { ?>
                <tr>
                    <td><?php echo $day; ?></td>
                    <?php for ($i = 1; $i <= 6; $i++) { ?>
                        <td>
                            <select name="timetable[<?php echo $day; ?>][hour<?php echo $i; ?>]">
                                <option value="">Select</option>
                                <?php
                                mysqli_data_seek($subjects_query, 0);
                                while ($subject = mysqli_fetch_assoc($subjects_query)) {
                                    $value = $subject['subject_name'] . " - " . $subject['teacher_name'];
                                    $selected = ($hours["hour$i"] == $value) ? "selected" : "";
                                    echo "<option value='$value' $selected>$value</option>";
                                }
                                ?>
                            </select>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>

        <button type="submit">Update Timetable</button>
    </form>

    <a href="teacher_timetable.php">⬅ Back to Timetable</a>

</body>
</html>

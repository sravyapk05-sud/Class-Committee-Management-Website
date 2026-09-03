<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Get teacher's email from session
$teacher_email = $_SESSION['teacher_email'];

// Handling form submission to mark attendance
if (isset($_POST['mark_attendance'])) {
    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_email => $status) {
        $status = ($status == "Present") ? "Present" : "Absent"; // Default to "Absent" if unchecked
        $query = "INSERT INTO attendance (student_email, date, status, teacher_email) 
                  VALUES ('$student_email', '$date', '$status', '$teacher_email')
                  ON DUPLICATE KEY UPDATE status='$status', teacher_email='$teacher_email'";
        $conn->query($query);
    }

    echo "<script>alert('Attendance marked successfully!');</script>";
}

// Fetch all students from the database
$students = $conn->query("SELECT email, firstName, lastName FROM students");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: white;
            text-align: center;
            padding: 20px;
        }

        .navbar {
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-left: 20px;
        }

        .logout-btn {
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
            margin-right: 20px;
        }

        .logout-btn:hover {
            background: #4facfe;
            color: white;
            transform: scale(1.05);
        }

        form {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: 20px auto;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 18px;
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        table {
            width: 100%;
            margin-top: 20px;
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

        input[type="checkbox"] {
            transform: scale(1.5);
            cursor: pointer;
        }

        .submit-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background: #ff7eb3;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #ff4b8f;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            form {
                width: 95%;
            }
            
            th, td {
                font-size: 14px;
                padding: 10px;
            }

            input[type="checkbox"] {
                transform: scale(1.2);
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo">Class Committee - Teacher</div>
        <a href="teacher_homepage.php" class="logout-btn">Go-Back</a>
    </div>

    <h1>Welcome, Teacher</h1>
    <h2>Mark Attendance</h2>

    <form method="POST">
        <label for="date">Select Date:</label>
        <input type="date" name="date" required>

        <table>
            <tr>
                <th>Student Name</th>
                <th>Email</th>
                <th>Present</th>
            </tr>
            <?php while ($row = $students->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['firstName'] . " " . $row['lastName']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <input type="checkbox" name="attendance[<?php echo $row['email']; ?>]" value="Present" checked>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <button type="submit" name="mark_attendance" class="submit-btn">Submit Attendance</button>
    </form>

    <script>
        // JavaScript to ensure unchecked checkboxes send "Absent" value
        document.querySelector("form").addEventListener("submit", function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                if (!checkbox.checked) {
                    let hiddenInput = document.createElement("input");
                    hiddenInput.type = "hidden";
                    hiddenInput.name = checkbox.name;
                    hiddenInput.value = "Absent";
                    document.querySelector("form").appendChild(hiddenInput);
                }
            });
        });
    </script>

</body>
</html>

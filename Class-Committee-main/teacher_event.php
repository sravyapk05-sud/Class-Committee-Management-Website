<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Handle event submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $event_date = $_POST['event_date'];
    $event_description = mysqli_real_escape_string($conn, $_POST['event_description']);

    $query = "INSERT INTO events (event_name, event_date, event_description) VALUES ('$event_name', '$event_date', '$event_description')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Event added successfully!'); window.location.href='teacher_event.php';</script>";
    } else {
        echo "<script>alert('Error adding event!');</script>";
    }
}

// Handle event deletion
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM events WHERE id=$event_id");
    echo "<script>alert('Event deleted!'); window.location.href='teacher_event.php';</script>";
}

// Fetch all events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Event Management</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input, textarea {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        button {
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            background: #007BFF;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .event-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007BFF;
            color: white;
        }

        .delete-btn {
            padding: 5px 10px;
            font-size: 14px;
            color: white;
            background: red;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background: darkred;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background: #28a745;
            text-decoration: none;
            border-radius: 8px;
        }

        .back-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <h2>Teacher Event Management</h2>

    <div class="container">
        <h3>Announce an Event</h3>
        <form method="POST">
            <input type="text" name="event_name" placeholder="Event Name" required>
            <input type="date" name="event_date" required>
            <textarea name="event_description" placeholder="Event Description" required></textarea>
            <button type="submit" name="add_event">Announce Event</button>
        </form>

        <h3>Upcoming Events</h3>
        <table class="event-table">
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($events)) { ?>
                <tr>
                    <td><?php echo $row['event_name']; ?></td>
                    <td><?php echo $row['event_date']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="teacher_homepage.php" class="back-btn">⬅ Back to Homepage</a>
    </div>

</body>
</html>

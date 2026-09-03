<?php
session_start();
include("connect.php");

if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['student_email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    
    $query = "INSERT INTO complaints (student_email, complaint_text, status) VALUES ('$email', '$complaint', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Complaint submitted successfully!'); window.location.href='student_complaints.php';</script>";
    } else {
        echo "<script>alert('Error submitting complaint!');</script>";
    }
}

// Fetch previous complaints of this student
$complaints_query = mysqli_query($conn, "SELECT * FROM complaints WHERE student_email='$email' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <style>
        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            padding: 20px;
        }

        h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            margin-bottom: 30px;
        }

        textarea {
            width: 100%;
            height: 120px;
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
            font-size: 16px;
            resize: none;
            background: rgba(255, 255, 255, 0.3);
            color: #333;
            transition: 0.3s;
        }

        textarea::placeholder {
            color: rgba(0, 0, 0, 0.6);
            transition: 0.3s;
        }

        textarea:focus::placeholder {
            color: transparent;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff7eb3;
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff4b8f;
            transform: scale(1.05);
        }

        /* Table Styling */
        table {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        table th {
            background: rgba(255, 255, 255, 0.3);
            font-weight: bold;
            text-transform: uppercase;
        }

        table tr:hover {
            background: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }

        .status-label {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }

        .status-label.done {
            background: #28a745;
            color: white;
        }

        .status-label.pending {
            background: #ffc107;
            color: black;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        a:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Responsive */
        @media (max-width: 450px) {
            form {
                width: 90%;
            }
            table {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <h2>Submit a Complaint</h2>

    <form method="POST">
        <textarea name="complaint" required placeholder="Write your complaint here..."></textarea><br>
        <button type="submit">Submit</button>
    </form>

    <h2>Your Previous Complaints</h2>

    <table>
        <tr>
            <th>Complaint</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($complaints_query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['complaint_text']); ?></td>
                <td>
                    <span class="status-label <?php echo ($row['status'] == 'Done') ? 'done' : 'pending'; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="student_homepage.php">⬅ Back</a>

</body>
</html>

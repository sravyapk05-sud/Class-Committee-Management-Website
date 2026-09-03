<?php
session_start();
include("connect.php");

if (!isset($_SESSION['teacher_email']) || $_SESSION['role'] != 'teacher') {
    header("Location: index.php");
    exit();
}

// Fetch complaints from the database
$complaints = mysqli_query($conn, "SELECT * FROM complaints ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            flex-direction: column;
            height: 100vh;
            padding: 20px;
        }

        h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 15px;
        }

        table {
            width: 90%;
            max-width: 800px;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        th {
            background: rgba(255, 255, 255, 0.3);
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:hover {
            background: rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff7eb3;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        a:hover {
            background: #ff4b8f;
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 600px) {
            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <h2>Student Complaints</h2>

    <table>
        <tr>
            <th>Complaint</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($complaints)) { ?>
            <tr>
                <td><?php echo $row['complaint_text']; ?></td>
                <td>
                    <input type="checkbox" class="status-checkbox" data-id="<?php echo $row['id']; ?>" 
                        <?php echo ($row['status'] == 'Done') ? 'checked' : ''; ?>>
                    <span class="status-label <?php echo ($row['status'] == 'Done') ? 'done' : ''; ?>" id="status_<?php echo $row['id']; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="teacher_homepage.php">⬅ Back to Homepage</a>

    <script>
        $(document).ready(function() {
            $(".status-checkbox").change(function() {
                let complaint_id = $(this).data("id");
                let new_status = $(this).prop("checked") ? "Done" : "Pending";

                $.ajax({
                    url: "update_complaint_status.php",
                    type: "POST",
                    data: { id: complaint_id, status: new_status },
                    success: function(response) {
                        if (response == "success") {
                            let label = $("#status_" + complaint_id);
                            label.text(new_status);
                            label.toggleClass("done", new_status == "Done");
                        } else {
                            alert("Failed to update status!");
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>

<?php
session_start();
include("connect.php");

if (!isset($_SESSION['student_email']) || $_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

// Fetch all events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");

$today = date("Y-m-d");
$past_events = [];
$today_events = [];
$upcoming_events = [];
$calendar_events = [];

// Categorize events
while ($row = mysqli_fetch_assoc($events)) {
    if ($row['event_date'] < $today) {
        $past_events[] = $row;
    } elseif ($row['event_date'] == $today) {
        $today_events[] = $row;
    } else {
        $upcoming_events[] = $row;
    }

    // Format for FullCalendar
    $calendar_events[] = [
        'title' => $row['event_name'],
        'start' => $row['event_date'],
        'description' => $row['event_description']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Events</title>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <style>

        /* Ensure the day names (Mon, Tue, etc.) are visible */
        .fc-col-header-cell {
            font-size: 14px !important; /* Adjust size */
            font-weight: bold;
            color: black !important; /* Ensure visibility */
            background: #f8f9fa; /* Light background */
            padding: 5px;
        }

        /* Adjust for small screens */
        @media (max-width: 768px) {
            .fc-header-toolbar {
                font-size: 12px !important; /* Reduce toolbar size */
            }

            .fc-col-header-cell {
                font-size: 12px !important; /* Make day names smaller but visible */
            }
        }


        /*Fix for Mobile View (For Small Screens) */
         @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .event-section, .calendar-container {
                width: 100%;
            }

            #calendar {
                font-size: 12px; /* Reduce font size */
                height: auto; /* Allow it to resize */
            }

            .fc-daygrid-day-number {
                font-size: 12px !important;
            }
        }
    
        /* Ensure calendar text remains visible */
        .fc-daygrid-day-number {
            font-size: 14px !important; /* Adjust font size */
            color: black !important; /* Ensure date numbers are visible */
        }

        .fc-daygrid-day {
            min-height: 40px; /* Maintain enough space for the date */
        }

        .fc-toolbar-title {
            font-size: 20px !important; /* Adjust title size */
        }

        /* Ensure events are readable on small screens */
        .fc-event-title {
            font-size: 12px !important;
            white-space: normal !important;
        }


        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #141e30, #243b55);
            color: white;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            font-size: 32px;
            color: #ffeb3b;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1100px;
            width: 100%;
        }

        /* Left Side - Event Descriptions */
        .event-section {
            width: 55%;
            padding: 20px;
        }

        .event-category {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 15px;
            text-align: left;
        }

        .event-category h3 {
            color: #ffeb3b;
        }

        .event-list {
            list-style: none;
            padding: 0;
        }

        .event-list li {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }

        /* Right Side - Calendar */
        .calendar-container {
            width: 100%;
            max-width: 450px; /* Adjust max-width as needed */
            padding: 10px;
            overflow: hidden;
        }

        #calendar {
            width: 100%;
            background: white;
            padding: 10px;
            border-radius: 10px;
        }

        /* Back Button */
        .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            color: white;
            background: #ff4081;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .back-btn:hover {
            background: #d81b60;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .event-section, .calendar-container {
                width: 90%;
            }

            #calendar {
                height: 350px;
            }
        }
    </style>
</head>
<body>

    <h2>📅 Student Event Calendar</h2>

    <div class="container">
        
        <!-- Left Side: Event Categories -->
        <div class="event-section">
            <div class="event-category">
                <h3>✅ Today's Events</h3>
                <ul class="event-list">
                    <?php if (!empty($today_events)) { 
                        foreach ($today_events as $event) {
                            echo "<li>{$event['event_name']} - {$event['event_description']}</li>";
                        }
                    } else { 
                        echo "<li>No events today.</li>";
                    } ?>
                </ul>
            </div>

            <div class="event-category">
                <h3>🔜 Upcoming Events</h3>
                <ul class="event-list">
                    <?php if (!empty($upcoming_events)) { 
                        foreach ($upcoming_events as $event) {
                            echo "<li>{$event['event_name']} ({$event['event_date']}) - {$event['event_description']}</li>";
                        }
                    } else { 
                        echo "<li>No upcoming events.</li>";
                    } ?>
                </ul>
            </div>

            <div class="event-category">
                <h3>⏳ Past Events</h3>
                <ul class="event-list">
                    <?php if (!empty($past_events)) { 
                        foreach ($past_events as $event) {
                            echo "<li>{$event['event_name']} ({$event['event_date']}) - {$event['event_description']}</li>";
                        }
                    } else { 
                        echo "<li>No past events.</li>";
                    } ?>
                </ul>
            </div>
        </div>

        <!-- Right Side: Calendar -->
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>

    <a href="student_homepage.php" class="back-btn">⬅ Back to Homepage</a>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        
        document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',  
                aspectRatio: 1.5,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                dayHeaderFormat: { weekday: 'short' }, // Ensure day names show (Mon, Tue, etc.)
                events: <?php echo json_encode($calendar_events); ?>,
                eventClick: function (info) {
                    alert('Event: ' + info.event.title + '\nDescription: ' + info.event.extendedProps.description);
                }
            });
            calendar.render();
        }
    });

    </script>

</body>
</html>

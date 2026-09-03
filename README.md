# Class-Committee-Management-Website
A web-based class management system developed using PHP and MySQL for managing student-teacher communication, attendance, complaints, events, feedback, timetables, and profiles.



## Objective

The Class Committee Management System is a web-based application designed to manage academic and class-related activities through separate **Student and Teacher modules**.

The system provides features for **attendance management, complaint handling, event management, student feedback, timetable management, and profile management**. It uses PHP sessions for role-based access and MySQL for storing and managing application data.

The objective is to provide a centralized platform that improves communication between students and teachers and simplifies routine class management activities.

### Skills Learned

* Web application development using PHP and MySQL.
* Role-based authentication and session management.
* Database design and MySQL integration.
* Student attendance management.
* Complaint submission and status tracking.
* Event and announcement management.
* Student feedback and survey handling.
* Timetable management.
* Profile and image upload management.
* Frontend development using HTML, CSS, JavaScript, and jQuery.

### Tools Used

* **PHP** for backend development.
* **MySQL** for database management.
* **HTML & CSS** for webpage structure and styling.
* **JavaScript & jQuery** for interactive functionality.
* **FullCalendar** for event management and calendar display.
* **XAMPP** for local server and database environment.
* **Visual Studio Code** for development.

## Steps

Below are the key steps taken in the development of the Class Committee Management System:

### 1. User Registration and Authentication

The system provides separate registration and login functionality for **students and teachers**.

PHP sessions are used to maintain user authentication and restrict access to features based on the user's role.

*Ref 1: User Authentication*
This step establishes secure role-based access for students and teachers.

### 2. Student Module

The student module allows students to access and manage class-related information.

Students can:

* View attendance records.
* Submit complaints.
* Track complaint status.
* View class events.
* Submit subject/teacher feedback.
* View the timetable.
* Manage their profile and profile photo.

*Ref 2: Student Module*
This module provides students with a centralized platform for accessing academic and class-related services.

### 3. Teacher Module

The teacher module provides tools for managing class activities.

Teachers can:

* Mark student attendance.
* View and update complaints.
* Create and manage events.
* View student feedback.
* Update the class timetable.
* Manage their profile and profile photo.

*Ref 3: Teacher Module*
This module allows teachers to manage and monitor important class activities from a single interface.

### 4. Complaint and Feedback Management

Students can submit complaints through the system, which are stored in the MySQL database with a **Pending** status.

Teachers can review complaints and update their status. Students can then view the updated status of their complaints.

The feedback system allows students to provide subject-specific feedback, which can be viewed by teachers.

*Ref 4: Complaint and Feedback Management*
This provides a structured communication channel between students and teachers.

### 5. Attendance and Timetable Management

Teachers can record student attendance for specific dates, while students can view their attendance records.

The timetable module allows teachers to manage class schedules, which are then made available to students through their dashboard.

*Ref 5: Attendance and Timetable Management*
This stage manages important academic information and makes it accessible to the appropriate users.

### 6. Event Management

Teachers can create and manage class events, while students can view upcoming events.

The system uses **FullCalendar** to provide an interactive calendar interface for displaying event information.

*Ref 6: Event Management*
This helps students and teachers keep track of important class activities and events.


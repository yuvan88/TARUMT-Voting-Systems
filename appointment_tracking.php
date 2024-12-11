<?php
session_start();
include('php/config.php');

// Ensure the user is logged in and their role is set in the session
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Fetch all appointments (voter, volunteer, candidate)
$query = "SELECT a.id, a.appointment_date, a.appointment_time, u.name, 'Voter' AS type, a.status FROM appointments a JOIN users u ON a.user_id = u.id
          UNION
          SELECT v.id, v.appointment_date, v.appointment_time, u.name, 'Volunteer' AS type, v.status FROM volunteer_appointments v JOIN users u ON v.volunteer_id = u.id
          UNION
          SELECT c.id, c.appointment_date, c.appointment_time, u.name, 'Candidate' AS type, c.status FROM candidate_appointments c JOIN users u ON c.candidate_id = u.id";

$result = mysqli_query($con, $query);

if (!$result) {
    die("Error fetching appointments: " . mysqli_error($con));
}

// Display appointments in a table
echo "<table border='1'>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['name']}</td>
            <td>{$row['appointment_date']}</td>
            <td>{$row['appointment_time']}</td>
            <td>{$row['type']}</td>
            <td>{$row['status']}</td>
            <td>
                <a href='cancel_appointment.php?id={$row['id']}'>Cancel</a> | 
                <a href='schedule_appointment.php?id={$row['id']}'>Reschedule</a>
            </td>
          </tr>";
}

echo "</table>";
?>
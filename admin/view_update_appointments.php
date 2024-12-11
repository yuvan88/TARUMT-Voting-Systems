<?php
// Assuming session is already started and user is authenticated
include 'db/connection.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch maintenance appointments from the database
$query = "SELECT * FROM maintenance_appointments ORDER BY maintenance_date ASC";
$result = mysqli_query($conn, $query);

echo "<h3>Scheduled Maintenance Appointments</h3>";
echo "<table border='1'>";
echo "<tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th>Actions</th></tr>";

// Display appointments in a table format
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['maintenance_date']}</td>
            <td>{$row['maintenance_time']}</td>
            <td>{$row['maintenance_type']}</td>
            <td>{$row['status']}</td>
            <td>
                <!-- Form for updating status -->
                <form method='POST' action='view_update_appointments.php'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <select name='status'>
                        <option value='scheduled' " . ($row['status'] == 'scheduled' ? 'selected' : '') . ">Scheduled</option>
                        <option value='completed' " . ($row['status'] == 'completed' ? 'selected' : '') . ">Completed</option>
                    </select>
                    <button type='submit' name='update_status'>Update Status</button>
                </form>
            </td>
          </tr>";
}
echo "</table>";

// Check if status update request has been made
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $appointmentId = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update the status of the selected maintenance appointment
    $updateQuery = "UPDATE maintenance_appointments SET status = '$status' WHERE id = $appointmentId";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<p>Maintenance status updated successfully!</p>";
    } else {
        echo "<p>Error updating status: " . mysqli_error($conn) . "</p>";
    }
}

?>

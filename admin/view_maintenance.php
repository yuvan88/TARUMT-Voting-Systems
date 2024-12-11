<?php
// Include the database connection
include 'db/connection.php';  // Adjust path based on your directory structure

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed.");
} else {
    echo "Database connected successfully.";  // Optional debug line
}

// Fetch all maintenance appointments from the database
$query = "SELECT * FROM maintenance_appointments ORDER BY maintenance_date ASC";
$result = mysqli_query($conn, $query);  // Using $conn for the connection

echo "<h3>Scheduled Maintenance Appointments</h3>";
echo "<table border='1'>";
echo "<tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th>Actions</th></tr>";

// Display each appointment
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['maintenance_date']}</td>
            <td>{$row['maintenance_time']}</td>
            <td>{$row['maintenance_type']}</td>
            <td>{$row['status']}</td>
            <td>
                <form method='POST' action='update_status.php'>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <select name='status'>
                        <option value='scheduled' " . ($row['status'] == 'scheduled' ? 'selected' : '') . ">Scheduled</option>
                        <option value='completed' " . ($row['status'] == 'completed' ? 'selected' : '') . ">Completed</option>
                    </select>
                    <button type='submit'>Update Status</button>
                </form>
            </td>
          </tr>";
}

echo "</table>";
?>

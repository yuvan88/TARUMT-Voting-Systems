<?php
// Include the database connection
include 'db/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form values are set before accessing them
    $maintenanceDate = isset($_POST['date']) ? mysqli_real_escape_string($conn, $_POST['date']) : '';
    $maintenanceTime = isset($_POST['time']) ? mysqli_real_escape_string($conn, $_POST['time']) : '';
    $maintenanceType = isset($_POST['maintenance_type']) ? mysqli_real_escape_string($conn, $_POST['maintenance_type']) : '';

    // Insert new maintenance appointment
    if (isset($_POST['schedule'])) {
        $query = "INSERT INTO maintenance_appointments (maintenance_date, maintenance_time, maintenance_type) 
                  VALUES ('$maintenanceDate', '$maintenanceTime', '$maintenanceType')";

        if (mysqli_query($conn, $query)) {
            echo "Maintenance appointment scheduled successfully!";
        } else {
            echo "Error scheduling maintenance appointment: " . mysqli_error($conn);
        }
    }

    // Update maintenance appointment
    if (isset($_POST['update'])) {
        $appointmentId = isset($_POST['appointment_id']) ? mysqli_real_escape_string($conn, $_POST['appointment_id']) : '';
        $query = "UPDATE maintenance_appointments 
                  SET maintenance_date = '$maintenanceDate', maintenance_time = '$maintenanceTime', maintenance_type = '$maintenanceType'
                  WHERE id = $appointmentId";

        if (mysqli_query($conn, $query)) {
            echo "Maintenance appointment updated successfully!";
        } else {
            echo "Error updating maintenance appointment: " . mysqli_error($conn);
        }
    }
}

// Fetch all maintenance appointments for viewing
$query = "SELECT * FROM maintenance_appointments";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Appointment</title>
</head>
<body>
    <h2>Schedule Maintenance Appointment</h2>
    <form method="POST">
        <label for="date">Maintenance Date:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="time">Maintenance Time:</label>
        <input type="time" id="time" name="time" required><br>

        <label for="maintenance_type">Maintenance Type:</label>
        <input type="text" id="maintenance_type" name="maintenance_type" required><br>

        <button type="submit" name="schedule">Schedule Maintenance</button>
    </form>

    <!-- Add navigation buttons to other pages -->
    <div>
        <button onclick="window.location.href='view_maintenance.php';">View Maintenance Appointments</button>
        <button onclick="window.location.href='update_status.php';">Update Maintenance Status</button>
    </div>

    
</body>
</html>

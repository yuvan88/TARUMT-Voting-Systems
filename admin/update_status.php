<?php
// Include the database connection
include 'db/connection.php';  // Adjust path if necessary

// Debugging: Check if connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Proceed if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input values to prevent SQL injection
    $appointmentId = mysqli_real_escape_string($con, $_POST['id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    
    // Update the status in the database
    $query = "UPDATE maintenance_appointments SET status = '$status' WHERE id = $appointmentId";

    if (mysqli_query($con, $query)) {
        // Redirect back to the appointment page after success
        header('Location: view_appointments.php?status=success');
        exit();
    } else {
        // Display error message if query fails
        echo "Error updating status: " . mysqli_error($con);
    }
}
?>

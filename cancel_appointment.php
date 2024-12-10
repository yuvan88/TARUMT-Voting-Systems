<?php
session_start();
include('php/config.php');

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied");
}

// Check if the appointment ID is provided
if (isset($_GET['id'])) {
    $appointment_id = mysqli_real_escape_string($con, $_GET['id']);

    // Cancel the appointment
    $cancel_query = "UPDATE appointments SET status = 'Cancelled' WHERE id = ?";
    $stmt = mysqli_prepare($con, $cancel_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $appointment_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Appointment canceled successfully.";
        } else {
            echo "Failed to cancel appointment. Please try again.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the cancel statement.";
    }
} else {
    echo "Invalid appointment ID.";
}
?>

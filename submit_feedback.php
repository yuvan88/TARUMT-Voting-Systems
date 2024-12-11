<?php
session_start();
include('db/connection.php');  // Ensure correct path to connection.php

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header('Location: login.php');
    exit();
}

// Check if appointment_id is set in session (make sure appointment is booked)
if (!isset($_SESSION['appointment_id'])) {
    die("Appointment ID not found. Please book an appointment first.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize form inputs
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $scheduling_efficiency = mysqli_real_escape_string($conn, $_POST['scheduling_efficiency']);
    $notification_effectiveness = mysqli_real_escape_string($conn, $_POST['notification_effectiveness']);
    $overall_satisfaction = mysqli_real_escape_string($conn, $_POST['overall_satisfaction']);
    $user_id = $_SESSION['id']; // Get the logged-in user's ID
    $appointment_id = $_SESSION['appointment_id']; // Get the appointment ID from the session

    // Insert feedback into the database
    $insert_feedback_query = "INSERT INTO feedback (user_id, appointment_id, rating, scheduling_efficiency, notification_effectiveness, overall_satisfaction) 
                               VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $insert_feedback_query);
    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "iiisss", $user_id, $appointment_id, $rating, $scheduling_efficiency, $notification_effectiveness, $overall_satisfaction);
        if (mysqli_stmt_execute($stmt_insert)) {
            echo "Thank you for your feedback!";
        } else {
            echo "Error submitting feedback.";
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        echo "Error preparing statement.";
    }
}
?>

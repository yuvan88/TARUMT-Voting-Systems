<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$con = mysqli_connect("localhost", "username", "password", "your_database");
if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to send reminder email
function sendReminder($voterEmail, $appointmentDate, $appointmentTime) {
    $subject = "Reminder: Your Voting Appointment";
    $message = "This is a reminder for your voting appointment on $appointmentDate at $appointmentTime.";
    $headers = "From: no-reply@voting.com";

    if (mail($voterEmail, $subject, $message, $headers)) {
        echo "Reminder sent to $voterEmail.<br>";
    } else {
        echo "Failed to send reminder to $voterEmail.<br>";
    }
}

// Function to check for appointments within 24 hours and send reminders
function checkAndSendReminders($con) {
    // Query to find appointments scheduled for the next day
    $query = "SELECT * FROM appointments WHERE appointment_date = CURDATE() + INTERVAL 1 DAY";
    $result = mysqli_query($con, $query);
    
    // Loop through each appointment
    while ($row = mysqli_fetch_assoc($result)) {
        $voterEmail = $row['email'];
        $appointmentDate = $row['appointment_date'];
        $appointmentTime = $row['appointment_time'];

        // Send reminder email
        sendReminder($voterEmail, $appointmentDate, $appointmentTime);
    }
}

// Run the function to send reminders
checkAndSendReminders($con);
?>

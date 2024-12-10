<?php
// Include the database connection file
include('php/config.php'); // Ensure this is correct

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $location = $_POST['location'];
    $user_id = 22; // Replace with the logged-in user ID

    // Insert data into the database
    $query = "INSERT INTO appointments (user_id, appointment_date, appointment_time, location) 
              VALUES ('$user_id', '$appointment_date', '$appointment_time', '$location')";
    
    if (mysqli_query($conn, $query)) {
        echo "Appointment scheduled successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

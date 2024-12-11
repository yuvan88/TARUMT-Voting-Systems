<?php
include 'db/connection.php';
include 'header.php';
// Check if candidate ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];


    // Delete the candidate from the database
    $sql = "DELETE FROM candidates WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: candidate_management.php");  // Redirect to candidates list after deletion
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "No candidate found to delete.";
}
?>

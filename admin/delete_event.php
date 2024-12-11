<?php
session_start();
include 'db/connection.php';
include 'header.php';
$eventId = isset($_GET['id']) ? $_GET['id'] : null;
if ($eventId) {
    // Delete associated candidates first
    $sqlDeleteCandidates = "DELETE FROM candidates_events WHERE event_id = $eventId";
    mysqli_query($conn, $sqlDeleteCandidates);

    // Then delete the event
    $sqlDeleteEvent = "DELETE FROM events WHERE id = $eventId";
    if (mysqli_query($conn, $sqlDeleteEvent)) {
        echo "<p>Event deleted successfully.</p>";
    } else {
        echo "<p>Error deleting event.</p>";
    }
}
?>

<?php
session_start();
include 'db/connection.php';
include 'header.php';
$eventId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$eventId) {
    echo "Event not found!";
    exit();
}

// Fetch event details
$sqlEvent = "SELECT * FROM events WHERE id = $eventId";
$eventResult = mysqli_query($conn, $sqlEvent);
$event = mysqli_fetch_assoc($eventResult);

// Fetch all candidates
$sqlCandidates = "SELECT * FROM candidates";
$candidatesResult = mysqli_query($conn, $sqlCandidates);

// Update event details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_event'])) {
    $eventName = mysqli_real_escape_string($conn, $_POST['event_name']);
    $eventDate = mysqli_real_escape_string($conn, $_POST['event_date']);
    $posterImage = $_FILES['poster_image']['name'];

    if ($posterImage) {
        $targetDir = "image/";
        $targetFile = $targetDir . basename($posterImage);
        move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetFile);

        $sqlUpdateEvent = "UPDATE events SET name='$eventName', poster_image='$posterImage', event_date='$eventDate' WHERE id = $eventId";
    } else {
        $sqlUpdateEvent = "UPDATE events SET name='$eventName', event_date='$eventDate' WHERE id = $eventId";
    }

    if (mysqli_query($conn, $sqlUpdateEvent)) {
        echo "<p>Event updated successfully.</p>";
    } else {
        echo "<p>Error updating event.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Event</title>
    </head>
        <header class="sticky-header">
            <div class="header-container">
                <h1 class="logo">Vote System</h1>
                <nav>
                    <a href="event_management.php">Manage event</a>
                    <a href="candidate_management.php">Manage Candidates</a>
                    <a href="staff_management.php">Manage Staff</a>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                </nav>
            </div>
        </header>
    <body>

        <h2>Edit Event</h2>

        <form action="edit_event.php?id=<?= $eventId; ?>" method="POST" enctype="multipart/form-data">
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" id="event_name" value="<?= $event['name']; ?>" required>

            <label for="event_date">Event Date</label>
            <input type="date" name="event_date" id="event_date" value="<?= $event['updated_at']; ?>" required>

            <label for="poster_image">Event Poster</label>
            <input type="file" name="poster_image" id="poster_image">

            <!-- Display current poster image -->
            <p>Current Poster: <img src="<?= $event['poster_image']; ?>" width="100" alt="Current Event Poster"></p>

            <button type="submit" name="edit_event">Update Event</button>
        </form>

    </body>
</html>

<style>
    /* General styles for the page */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fc;
    }

    /* Sticky Header Styles */
    .sticky-header {
        position: sticky;
        top: 0;
        background-color: white;
        color: black;
        padding: 15px;
        z-index: 1000;
    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .sticky-header h1 {
        margin: 0;
    }

    .sticky-header nav {
        display: flex;
        gap: 20px;
    }

    .sticky-header nav a {
        color: black;
        text-decoration: none;
        font-size: 16px;
        padding: 5px 10px;
        transition: background-color 0.3s ease;
    }

    .sticky-header nav a:hover {
        background-color: #ddd;
    }

    /* Container for the content */
    .container {
        margin: 20px auto;
        max-width: 1200px;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Candidate Management Section */
    .candidate-management {
        margin-top: 20px;
    }

    /* Form Styles */
    form {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    form input[type="text"], form input[type="file"] {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    form label {
        font-size: 14px;
        margin-bottom: 5px;
    }

    form button[type="submit"] {
        background-color: #87CEEB; /* Light Blue */
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    form button[type="submit"]:hover {
        background-color: #00BFFF; /* Slightly darker blue */
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f4f7fc;
        color: #333;
    }

    td img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
    }
</style>
<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch candidates for the checkbox list
$sqlCandidates = "SELECT * FROM candidates";
$candidatesResult = mysqli_query($conn, $sqlCandidates);

// Handle form submission for creating an event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    // Sanitize input
    $eventName = mysqli_real_escape_string($conn, $_POST['event_name']);
    $eventDate = mysqli_real_escape_string($conn, $_POST['updated_at']);
    $posterImage = $_FILES['poster_image']['name'];
    $targetDir = "image/";
    $targetFile = $targetDir . basename($posterImage);

    // Move uploaded poster image
    if (move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetFile)) {
        // File uploaded successfully
    } else {
        echo "Error uploading file.";
    }

    // Insert event into the database
    $sqlEvent = "INSERT INTO events (name, poster_image, updated_at) VALUES ('$eventName', '$posterImage', '$eventDate')";
    if (mysqli_query($conn, $sqlEvent)) {
        $eventId = mysqli_insert_id($conn);  // Get the last inserted event ID

        // Assign candidates to the event
        if (!empty($_POST['candidate_ids'])) {
            foreach ($_POST['candidate_ids'] as $candidateId) {
                $sqlCandidateEvent = "INSERT INTO candidates_events (candidate_id, event_id) VALUES ('$candidateId', '$eventId')";
                if (!mysqli_query($conn, $sqlCandidateEvent)) {
                    echo "Error assigning candidate: " . mysqli_error($conn);
                }
            }
        }

        // Set success message and redirect
        $_SESSION['message'] = "Event created successfully!";
        header("Location: event_management.php");
        exit();  // Stop further script execution
    } else {
        // Check for SQL errors
        echo "Error creating event: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="sticky-header">
    <div class="header-container">
        <h1 class="logo">Vote System</h1>
        <nav>
            <a href="event_management.php">Manage Event</a>
            <a href="candidate_management.php">Manage Candidates</a>
            <a href="staff_management.php">Manage Staff</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Create New Event</h2>

    <!-- Display Message -->
    <?php if (isset($_SESSION['message'])) {
        echo "<div class='message'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
    } ?>

    <!-- Event Form -->
    <form method="POST" action="create_event.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" id="event_name" required>
        </div>

        <div class="form-group">
            <label for="updated_at">Event Date</label>
            <input type="date" name="updated_at" id="updated_at" required>
        </div>

        <div class="form-group">
            <label for="poster_image">Event Poster Image</label>
            <input type="file" name="poster_image" id="poster_image" accept="image/*" required>
        </div>
        <h3>Select Candidates (at least 2 candidates)</h3>
        <div class="candidate-selection">
        <?php while ($candidate = mysqli_fetch_assoc($candidatesResult)) { ?>
            <label class="candidate-checkbox">
                <input type="checkbox" name="candidate_ids[]" value="<?= $candidate['id']; ?>" />
                <div class="candidate-image">
                    <img src="image/<?= $candidate['photo']; ?>" alt="Candidate Image" width="100" height="100">
                </div>
                <p><?= $candidate['name']; ?> - <?= $candidate['party']; ?></p>
            </label>
        <?php } ?>
    </div>

        <button type="submit" name="create_event" class="button">Create Event</button>
    </form>
</div>

</body>
</html>
<style>/* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

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

/* Container for Main Content */
.container {
    width: 80%;
    margin: 30px auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
}

form .form-group {
    margin-bottom: 20px;
}

form .form-group label {
    font-weight: bold;
}

form .form-group input[type="text"],
form .form-group input[type="date"],
form .form-group input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

form .checkbox-group {
    display: flex;
    flex-wrap: wrap;
}

form .checkbox-group div {
    width: 45%;
    margin-right: 10px;
}

form .checkbox-group input {
    margin-right: 10px;
}

button.button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

button.button:hover {
    background-color: #45a049;
}

/* Success Message */
.message {
    color: green;
    background-color: #e8f9e8;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
}

/* Table and List Styling */
.candidate-selection {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.candidate-checkbox {
    display: inline-block;
    text-align: center;
    position: relative;
}

.candidate-checkbox input[type="checkbox"] {
    display: none;
}

.candidate-checkbox:hover img {
    opacity: 0.8;
    cursor: pointer;
}

.candidate-checkbox input[type="checkbox"]:checked + .candidate-image img {
    border: 10px solid #4CAF50;
}

.candidate-image img {
    display: block;
    border-radius: 50%;
    transition: border 0.2s ease-in-out;
    cursor: pointer;
    margin: 20%;
}

.candidate-checkbox p {
    margin-top: 5px;
    font-size: 14px;
}

/* Form Styling */
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
}

form input, form select, form button {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}

form button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

form button:hover {
    background-color: #45a049;
}

</style>
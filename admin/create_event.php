<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Enable error reporting for debugging (production should disable this)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors for security reasons

// Fetch candidates for the checkbox list securely
$sqlCandidates = "SELECT * FROM candidates";
$candidatesResult = mysqli_query($conn, $sqlCandidates);

// Handle form submission for creating an event
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_event'])) {
    // Sanitize and validate input data
    $eventName = mysqli_real_escape_string($conn, trim($_POST['event_name']));
    $eventDate = mysqli_real_escape_string($conn, $_POST['updated_at']);

    // Validate event name and date
    if (empty($eventName) || empty($eventDate)) {
        $_SESSION['message'] = "Event name and date are required!";
        header("Location: create_event.php");
        exit();
    }

    // Handle file upload securely
    if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] == UPLOAD_ERR_OK) {
        $posterImage = $_FILES['poster_image']['name'];
        $targetDir = "image/";
        $targetFile = $targetDir . basename($posterImage);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is a valid image type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $_SESSION['message'] = "Only JPG, JPEG, PNG & GIF files are allowed!";
            header("Location: create_event.php");
            exit();
        }

        // Check file size (limit to 5MB)
        if ($_FILES['poster_image']['size'] > 5 * 1024 * 1024) {
            $_SESSION['message'] = "File size should not exceed 5MB!";
            header("Location: create_event.php");
            exit();
        }

        // Rename file to avoid conflicts
        $newFileName = uniqid("poster_", true) . "." . $imageFileType;
        $targetFile = $targetDir . $newFileName;

        // Move uploaded file to the target directory
        if (!move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetFile)) {
            $_SESSION['message'] = "Error uploading file.";
            header("Location: create_event.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "No file uploaded or error uploading file.";
        header("Location: create_event.php");
        exit();
    }

    // Insert event into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO events (name, poster_image, updated_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $eventName, $newFileName, $eventDate);

    if ($stmt->execute()) {
        $eventId = $stmt->insert_id; // Get the last inserted event ID

        // Assign candidates to the event
        if (!empty($_POST['candidate_ids'])) {
            $stmtCandidateEvent = $conn->prepare("INSERT INTO candidates_events (candidate_id, event_id) VALUES (?, ?)");
            foreach ($_POST['candidate_ids'] as $candidateId) {
                $stmtCandidateEvent->bind_param("ii", $candidateId, $eventId);
                if (!$stmtCandidateEvent->execute()) {
                    $_SESSION['message'] = "Error assigning candidate: " . $stmtCandidateEvent->error;
                    header("Location: create_event.php");
                    exit();
                }
            }
        }

        // Set success message and redirect
        $_SESSION['message'] = "Event created successfully!";
        header("Location: event_management.php");
        exit();  // Stop further script execution
    } else {
        // Log the error (in production, log to a file instead of displaying)
        error_log("Error creating event: " . $stmt->error);
        $_SESSION['message'] = "Error creating event. Please try again.";
        header("Location: create_event.php");
        exit();
    }

    // Close prepared statements
    $stmt->close();
    if (isset($stmtCandidateEvent)) {
        $stmtCandidateEvent->close();
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

<style>
    /* (Your existing CSS remains the same) */
</style>

<style>
    /* General Body Styling */
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

    .candidate-checkbox input[type="checkbox"]:checked+.candidate-image img {
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

    form input,
    form select,
    form button {
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
<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Fetch all events with prepared statements
$stmt = $conn->prepare("SELECT * FROM events");
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
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

        <!-- Display Message -->
        <?php if (isset($_SESSION['message'])) {
            echo "<div class='message'>" . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . "</div>";
            unset($_SESSION['message']);
        } ?>

        <div class="event-list">
            <h2>Existing Events</h2>

            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Date</th>
                        <th>Event Poster</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) { ?>
                        <tr>
                            <td><?= htmlspecialchars($event['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($event['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>

                            <!-- Display event poster image -->
                            <td>
                                <?php if (!empty($event['poster_image'])) { ?>
                                    <img src="<?= htmlspecialchars($event['poster_image'], ENT_QUOTES, 'UTF-8'); ?>"
                                        alt="Event Poster" width="100">
                                <?php } else { ?>
                                    <p>No image available</p>
                                <?php } ?>
                            </td>

                            <td>
                                <a href="edit_event.php?id=<?= urlencode($event['id']); ?>" class="button">Edit</a>
                                <a href="delete_event.php?id=<?= urlencode($event['id']); ?>" class="button"
                                    onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="add-admin-btn">
                <a href="create_event.php" class="button">Create Event</a>
            </div>
        </div>

    </div>

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

    form input[type="text"],
    form input[type="file"] {
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
        background-color: #87CEEB;
        /* Light Blue */
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    form button[type="submit"]:hover {
        background-color: #00BFFF;
        /* Slightly darker blue */
    }

    /* Table Styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
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
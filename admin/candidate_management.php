<?php
include 'db/connection.php';
include 'header.php';
// Fetch all candidates from the database
$sql = "SELECT * FROM candidates";
$candidates = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

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

    <div class="container">
        <h2>Manage Candidates</h2>

        <!-- Button to Add New Candidate -->
        <div class="add-candidate-btn">
            <a href="add_candidate.php" class="button">Add New Candidate</a>
        </div>

        <!-- Display All Candidates -->
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($candidate = mysqli_fetch_assoc($candidates)) { ?>
                    <tr>
                        <td>
                            <!-- Display candidate's photo, if it exists -->
                            <?php if (!empty($candidate['photo'])) { ?>
                                <img src="image/<?= $candidate['photo']; ?>" alt="Candidate Photo" width="100" height="100">
                            <?php } else { ?>
                                <img src="image/default-photo.png" alt="No Photo" width="100" height="100">
                            <?php } ?>
                        </td>
                        <td><?= $candidate['name']; ?></td>
                        <td><?= $candidate['party']; ?></td>
                        <td>
                            <a href="edit_candidate.php?id=<?= $candidate['id']; ?>" class="button">Edit</a>
                            <a href="delete_candidate.php?id=<?= $candidate['id']; ?>" class="button"
                                onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
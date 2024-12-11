<?php
include 'db/connection.php';
include 'header.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_candidate'])) {
    $name = $_POST['name'];
    $party = $_POST['party'];
    
    // Handle photo upload
    $photo = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo_name = $_FILES['photo']['name'];
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_extension = pathinfo($photo_name, PATHINFO_EXTENSION);
        $new_photo_name = time() . '.' . $photo_extension; // Create a unique name for the photo
        $photo_path = 'image/' . $new_photo_name; // Photo directory path

        // Upload the photo to the server
        if (move_uploaded_file($photo_tmp_name, $photo_path)) {
            $photo = $new_photo_name; // Store the photo name in the database
        }
    }

    // Insert new candidate into the database
    $sql = "INSERT INTO candidates (name, party, photo) VALUES ('$name', '$party', '$photo')";
    if (mysqli_query($conn, $sql)) {
        header("Location: candidate_management.php");  // Redirect to candidates list after adding
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Candidate</title>
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
    <form method="POST" action="add_candidate.php" enctype="multipart/form-data">
        <label for="name">Candidate Name</label>
        <input type="text" name="name" id="name" required>

        <label for="party">Party</label>
        <input type="text" name="party" id="party" required>

        <label for="photo">Upload Photo</label>
        <input type="file" name="photo" id="photo" accept="image/*">

        <button type="submit" name="add_candidate">Add Candidate</button>
    </form>
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


<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Fetch all admin records securely
$sql = "SELECT * FROM admins";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
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

    <!-- Display Message -->
    <?php if (isset($_SESSION['message'])) {
        echo "<div class='message'>" . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . "</div>";
        unset($_SESSION['message']);
    } ?>

    <!-- Admin List (Read) -->
    <div class="admin-list">
        <h2>Admin List</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($admin = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($admin['role'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="edit_admin.php?id=<?= urlencode($admin['id']); ?>" class="button">Edit</a>
                        <a href="delete_admin.php?id=<?= urlencode($admin['id']); ?>" class="button" onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Admin Button -->
    <div class="add-admin-btn">
        <a href="add_admin.php" class="button">Add New Admin</a>
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

/* Add Admin Button */
.add-admin-btn {
    margin-top: 20px;
    text-align: right;
}

.add-admin-btn a {
    background-color: #87CEEB; /* Light Blue */
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.add-admin-btn a:hover {
    background-color: #00BFFF; /* Slightly darker blue */
}
</style>

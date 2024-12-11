<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Handle Add New Admin (Create)
if (isset($_POST['add_admin'])) {
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    // Ensure all fields are provided
    if ($username && $password && $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $sql = "INSERT INTO admins (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Admin added successfully!";
            header('Location: staff_management.php');
            exit;
        } else {
            $_SESSION['message'] = "Error adding admin: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['message'] = "All fields are required to add a new admin.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
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
        echo "<div class='message'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
    } ?>

    <!-- Add Admin Form -->
    <form action="add_admin.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="" required>

        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="Admin">Admin</option>

            <option value="User">User</option>
        </select>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="" required>

        <button type="submit" name="add_admin">Add Admin</button>
    </form>

</div>

</body>
</html>

<style>/* Sticky Header Styles */
/* Responsive Design */
@media (max-width: 768px) {
    .header-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .sticky-header nav {
        flex-direction: column;
        align-items: flex-start;
    }

    table {
        font-size: 14px;
    }

    .button {
        padding: 8px 12px;
        font-size: 14px;
    }
}

/* Container styles for the page */
.container {
    margin: 20px auto;
    max-width: 1200px;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Add Admin Button */
.add-admin-btn {
    margin-top: 20px;
    text-align: center;
}

.add-admin-btn a {
    background-color: #1e88e5;
    padding: 12px 20px;
    color: white;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.add-admin-btn a:hover {
    background-color: #1565c0;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    border-radius: 5px;
    overflow: hidden;
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

/* Button Styles */
.button {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    background-color: #5c6bc0;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #3f4f91;
}

button[type="submit"] {
    background-color: #87CEEB;
    color: white;
    border: none;
    cursor: pointer;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #45a049;
}

/* Form Styling */
form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
}

form input[type="text"],
form input[type="password"],
form select {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

form label {
    font-size: 14px;
    margin-bottom: 5px;
}

/* Form Buttons */
form .submit-btn {
    background-color: #1e88e5;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    font-size: 16px;
    text-align: center;
    transition: background-color 0.3s ease;
}

form .submit-btn:hover {
    background-color: #1565c0;
}

</style>

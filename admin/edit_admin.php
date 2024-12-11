<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Check if admin ID is provided
if (isset($_GET['id'])) {
    $admin_id = intval($_GET['id']); // Sanitize input

    // Fetch admin data for editing
    $sql = "SELECT * FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
    } else {
        $_SESSION['message'] = "Admin not found.";
        header("Location: staff_management.php");
        exit;
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: staff_management.php");
    exit;
}

// Handle update admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_admin'])) {
    $admin_id = intval($_POST['admin_id']);
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $role = mysqli_real_escape_string($conn, trim($_POST['role']));
    $password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, trim($_POST['password'])) : null;

    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET username = ?, role = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $username, $role, $hashed_password, $admin_id);
    } else {
        $sql = "UPDATE admins SET username = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $username, $role, $admin_id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Admin updated successfully!";
        header("Location: staff_management.php");
        exit;
    } else {
        $_SESSION['message'] = "Error updating admin: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="sticky-header">
    <div class="header-container">
        <h1 class="logo">Vote System</h1>
        <nav>
            <a href="event_management.php">Manage Events</a>
            <a href="candidate_management.php">Manage Candidates</a>
            <a href="staff_management.php">Manage Staff</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Edit Admin</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message"> <?= $_SESSION['message']; unset($_SESSION['message']); ?> </div>
    <?php endif; ?>

    <form action="edit_admin.php?id=<?= $admin_id ?>" method="post">
        <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['id']); ?>">

        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= htmlspecialchars($admin['username']); ?>" required>

        <label for="role">Role</label>
        <select name="role" id="role" required>
            <option value="Admin" <?= $admin['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="User" <?= $admin['role'] === 'User' ? 'selected' : ''; ?>>User</option>
        </select>

        <label for="password">Password (Leave blank to keep current)</label>
        <input type="password" name="password" id="password">

        <button type="submit" name="update_admin">Update Admin</button>
    </form>
</div>
</body>
</html>

<style>
/* Sticky Header Styles */
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background-color: grey;
    color: black;
}

.header-container nav a {
    color: white;
    text-decoration: none;
    margin: 0 10px;
    transition: color 0.3s;
}

.header-container nav a:hover {
    color: #c1e1ff;
}

/* Page Container */
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.message {
    background-color: #dff0d8;
    color: #3c763d;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #d6e9c6;
}

form label {
    display: block;
    margin: 10px 0 5px;
    font-weight: bold;
}

form input[type="text"],
form input[type="password"],
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    background-color: #1e88e5;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #1565c0;
}
</style>

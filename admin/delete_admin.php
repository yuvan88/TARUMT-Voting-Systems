<?php
session_start();
include 'db/connection.php';
include 'header.php';
// Handle Delete Admin (Delete)
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];
    $sql = "DELETE FROM admins WHERE id = $admin_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Admin deleted successfully!";
        header("Location: staff_management.php");
    } else {
        $_SESSION['message'] = "Error deleting admin: " . mysqli_error($conn);
        header("Location: staff_management.php");
    }
}
?>

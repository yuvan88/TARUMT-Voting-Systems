<?php
    session_start();      // Start the session
    session_destroy();    // Destroy all session data

    // Redirect with a message indicating logout success
    header("Location: ../index.php?message=loggedout");
    exit();
?>

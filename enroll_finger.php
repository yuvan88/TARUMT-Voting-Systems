<!DOCTYPE html>
<?php
// Ensure user_id is passed via GET
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Finger</title>
    <style>
        /* Basic styling for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        h1 {
            font-size: 1.8em;
            color: #333;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            color: #007bff;
            font-weight: bold;
            display: none; /* Initially hidden */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enroll Finger</h1>
        
        <!-- Form to send user_id as location -->
        <form action="http://192.168.1.33:8888/enroll_finger" method="post" onsubmit="showMessage()">
            <!-- Hidden location (same as user_id) -->
            <input type="hidden" name="location" value="<?php echo $user_id; ?>">

            <label for="location">Enroll Fingerprint</label>
            <input type="hidden" id="location" name="location" value="<?php echo $user_id; ?>" readonly>
            
            <button type="submit">Enroll Finger</button>
            
        </form>
            <a href="enroll_finger.php?user_id=<?php echo $user_id; ?>"><button>Try Again</button></a>
        
        
        <p class="message" id="fingerMessage">Place your finger on the sensor...</p>
        
        <!-- If the fingerprint fails, you can reload the form with the user_id parameter -->
        <div class="message" id="errorMessage" style="display: none;">
            <p>Fingerprint enrollment failed. Please try again.</p>
        </div>
    </div>

    <script>
        function showMessage() {
            // Show the message when the form is submitted
            const message = document.getElementById('fingerMessage');
            message.style.display = 'block';
        }

        // You can modify this to display an error message if needed
        function showError() {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'block';
        }
    </script>
</body>
</html>

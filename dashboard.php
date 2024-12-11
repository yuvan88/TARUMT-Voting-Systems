<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TARUMT Voting System - Voting Results</title>
    <!-- External CSS link -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: lightblue;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logo img {
            width: 200px;
            max-width: 100%;
            height: auto;
        }

        .navbar {
            display: flex;
            list-style: none;
        }

        .navbar a {
            text-decoration: none;
            color: black;
            margin: 0 15px;
            font-size: 1rem;
            line-height: 50px;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #007BFF;
        }

        #menu-btn {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                display: none;
                flex-direction: column;
                background-color: #40e0d0;
                position: absolute;
                top: 60px;
                right: 0;
                width: 100%;
                text-align: center;
            }

            .navbar.active {
                display: flex;
            }

            #menu-btn {
                display: block;
            }
        }

        /* Container to center the content */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            margin-top: 80px;
            /* Added margin to avoid overlap with fixed header */
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            table-layout: fixed;
            /* Ensures all columns have a consistent width */
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            word-wrap: break-word;
            /* Prevents content from overflowing */
        }

        table th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            /* Centers header text */
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table td {
            text-align: center;
            /* Center-aligns content in cells */
        }

        .no-results {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 20px;
        }

        /* Button Styling */
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn:active {
            background-color: #3e8e41;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="index.php">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="appointment.php">Appointment</a>
            <a href="voter.php">Voter</a>
            <a href="#review">Review</a>
            <a href="#blogs">Blogs</a>
            <a href="dashboard.php">Dashboard</a>
            <!-- <a href="register.php">Logout</a> -->
        </nav>
        <div id="menu-btn" class="fas fa-bars">☰</div>
    </header>

    <!-- Container for Voting Results -->
    <div class="container">
        <h2>Voting Results</h2>
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "votingsystem";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch voting results
        $sql = "SELECT president, COUNT(*) as vote_count FROM votes GROUP BY president";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>President</th><th>Votes</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["president"] . "</td><td>" . $row["vote_count"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-results'>No results found.</p>";
        }

        $conn->close();
        ?>

        <!-- Buttons Below Table -->
        <div class="btn-container">
            <button class="btn" onclick="window.location.href='voter_appointments.php'">Voter Appointment</button>
            <button class="btn" onclick="window.location.href='election_analysis.php'">Election Analysis</button>
        </div>
    </div>

    <!-- JS file link (for the menu toggle) -->
    <script src="js/script.js"></script>

</body>

</html>
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

// Fetch voter turnout
$sql = "SELECT status, COUNT(*) as turnout FROM voter_appointments GROUP BY status";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Turnout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Basic page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header styling */
        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-top: 30px;
        }

        /* Table styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Message if no data found */
        .no-data {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        /* Button styling */
        .button-container {
            text-align: center;
            margin-top: 30px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            transition: background-color 0.3s;
        }

        .button-container button:hover {
            background-color: #45a049;
        }

        /* Header section styling */
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
    </style>
</head>

<body>

    <!-- header section starts -->
    <header class="header">
        <a href="#" class="logo">
            <img src="image/tarumt.png" alt="TARUMT Logo">
        </a>
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#rule">Rule</a>
            <a href="#staff">Staff</a>
            <a href="appointment.php">Appointment</a>
            <a href="voter.php">Voter</a>
            <a href="#review">Review</a>
            <a href="#blogs">Blogs</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="register.php">Logout</a>
        </nav>
        <div id="menu-btn" class="fas fa-bars">☰</div>
    </header>
    <!-- header section ends -->

    <h2>Voter Turnout</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table><tr><th>Status</th><th>Count</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["status"] . "</td><td>" . $row["turnout"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-data'>No turnout data found.</p>";
    }

    $conn->close();
    ?>

    <!-- Buttons below the table -->
    <div class="button-container">
        <button onclick="location.href='dashboard.php'">Dashboard</button>
        <button onclick="location.href='election_analysis.php'">Election Analysis</button>
    </div>

    <!-- JS file link -->
    <script src="js/script.js"></script>
    <script>
        // JavaScript for menu toggle on mobile
        document.getElementById('menu-btn').addEventListener('click', function () {
            document.querySelector('.navbar').classList.toggle('active');
        });
    </script>

</body>

</html>
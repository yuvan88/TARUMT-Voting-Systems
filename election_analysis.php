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

// Fetch voting patterns and demographics from the users table
$sql = "SELECT 
            users.id, 
            users.age, 
            votes.president
        FROM votes
        JOIN users ON votes.user_id = users.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Election Analysis</h2>";
    echo "<table border='1'>
            <tr>
                <th>Age Group</th>
                <th>President</th>
                <th>Votes</th>
            </tr>";

    // Declare an array to store counts of votes for each age group
    $age_groups = [
        '0-12' => [],
        '13-25' => [],
        '26-35' => [],
        '36-45' => [],
        '46-55' => [],
        '56-65' => [],
        '66+' => []
    ];

    while ($row = $result->fetch_assoc()) {
        // Get the age directly without decryption
        $age = $row["age"];

        // Determine the age group
        if ($age >= 0 && $age <= 12) {
            $age_group = '0-12';
        } elseif ($age >= 13 && $age <= 25) {
            $age_group = '13-25';
        } elseif ($age >= 26 && $age <= 35) {
            $age_group = '26-35';
        } elseif ($age >= 36 && $age <= 45) {
            $age_group = '36-45';
        } elseif ($age >= 46 && $age <= 55) {
            $age_group = '46-55';
        } elseif ($age >= 56 && $age <= 65) {
            $age_group = '56-65';
        } elseif ($age > 65) {
            $age_group = '66+';
        }

        // Group the votes by age group and president
        $age_groups[$age_group][$row["president"]] = isset($age_groups[$age_group][$row["president"]]) ? $age_groups[$age_group][$row["president"]] + 1 : 1;
    }

    // Display the results
    foreach ($age_groups as $age_group => $presidents) {
        foreach ($presidents as $president => $vote_count) {
            echo "<tr>
                    <td>" . $age_group . "</td>
                    <td>" . $president . "</td>
                    <td>" . $vote_count . "</td>
                  </tr>";
        }
    }

    echo "</table>";
} else {
    echo "No analysis data found.";
}

$conn->close();
?>

<style>
    /* General body styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        color: #333;
        padding-top: 20px;
    }

    /* Table styling */
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    /* Message styling */
    .message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        margin: 20px;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
    }

    .message p {
        margin: 0;
    }

    /* General container for the content */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Styling for links */
    a {
        color: #4CAF50;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Buttons styling */
    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    .action-btn {
        padding: 10px 20px;
        margin: 5px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .action-btn:hover {
        background-color: #45a049;
    }
</style>

<!-- Buttons for Refresh, Export, Next, and Back -->
<div class="button-container">
    <!-- Next button (example link) -->
    <button class="action-btn" onclick="location.href='dashboard.php'">Dashboard</button>

    <!-- Back button (example link) -->
    <button class="action-btn" onclick="location.href='voter_appointments.php'">Voter Appointments</button>
</div>

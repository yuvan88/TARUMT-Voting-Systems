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
    // Set headers to download the file as CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="election_analysis.csv"');
    
    // Create the CSV file
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Age Group', 'President', 'Votes']);

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

    // Write the results to the CSV
    foreach ($age_groups as $age_group => $presidents) {
        foreach ($presidents as $president => $vote_count) {
            fputcsv($output, [$age_group, $president, $vote_count]);
        }
    }

    fclose($output);
} else {
    echo "No analysis data found.";
}

$conn->close();
?>

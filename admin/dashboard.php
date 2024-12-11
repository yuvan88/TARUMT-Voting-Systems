<?php
session_start();
include 'db/connection.php';
include 'header.php';
// Fetch total votes and votes for each candidate
$sql = "SELECT * FROM events";
$events = mysqli_query($conn, $sql);

// If an event is selected, fetch candidates and votes for that event
$candidates = [];
$totalVotes = 0;
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$event = null; // Initialize $event variable

if ($eventId) {
    // Fetch event details
    $eventSql = "SELECT * FROM events WHERE id = $eventId";
    $eventResult = mysqli_query($conn, $eventSql);
    $event = mysqli_fetch_assoc($eventResult);

    if ($event) {
        // Fetch candidates for the selected event
        $sql = "SELECT c.name, c.party, COUNT(v.candidate_id) AS vote_count
                FROM candidates c
                JOIN candidates_events ce ON c.id = ce.candidate_id
                LEFT JOIN votes v ON c.id = v.candidate_id
                WHERE ce.event_id = $eventId
                GROUP BY c.id";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $candidates[] = $row;
            $totalVotes += $row['vote_count'];
        }
    } else {
        // If the event doesn't exist
        echo "<p>Event not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vote Percentage</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

        <!-- Sticky Header Section -->
        <header class="sticky-header">
            <div class="header-container">
                <h1 class="logo">Vote System</h1>
                <nav>
                    <a href="event_management.php">Manage event</a>
                    <a href="candidate_management.php">Manage Candidates</a>
                    <a href="staff_management.php">Manage Staff</a>
                    <a href="logout.php">Logout</a>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="vote-percentage">
            <h2>Vote Percentage</h2>

            <!-- Display list of events with poster images -->
            <div class="events">
                <h3>Select an Event</h3>
                <div class="event-list">
                    <?php while ($eventData = mysqli_fetch_assoc($events)) { ?>
                        <div class="event-card">
                            <a href="dashboard.php?event_id=<?= $eventData['id']; ?>">
                                <img src="<?= $eventData['poster_image']; ?>" alt="<?= $eventData['name']; ?>" class="event-poster">
                                <h4><?= $eventData['name']; ?></h4>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Display vote chart if an event is selected -->
            <?php if ($event && !empty($candidates)) { ?>
                <h3>Vote Distribution for <?= $event['name']; ?></h3>

                <div class="chart-container">
                    <canvas id="voteChart" width="300" height="300"></canvas>
                </div>

                <script>
                    const candidates = <?php echo json_encode($candidates); ?>;
                    const candidateNames = candidates.map(candidate => candidate.name);
                    const votes = candidates.map(candidate => candidate.vote_count);
                    const totalVotes = <?php echo $totalVotes; ?>;

                    // Calculate vote percentages
                    const percentages = votes.map(vote => (vote / totalVotes) * 100);

                    const ctx = document.getElementById('voteChart').getContext('2d');
                    const voteChart = new Chart(ctx, {
                        type: 'pie', // Can switch to 'bar' for bar chart
                        data: {
                            labels: candidateNames,
                            datasets: [{
                                    label: 'Vote Percentage',
                                    data: percentages,
                                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300'],
                                    borderColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FFC300'],
                                    borderWidth: 1
                                }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                </script>
            <?php } elseif ($event && empty($candidates)) { ?>
                <p>No candidates have been registered for this event yet.</p>
            <?php } else { ?>
                <p>Please select an event to view the vote percentage.</p>
            <?php } ?>
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 Vote System. All Rights Reserved.</p>
        </footer>

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

    /* Candidate Management Section */
    .candidate-management {
        margin-top: 20px;
    }

    /* Form Styles */
    form {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
    }

    form input[type="text"], form input[type="file"] {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    form label {
        font-size: 14px;
        margin-bottom: 5px;
    }

    form button[type="submit"] {
        background-color: #87CEEB; /* Light Blue */
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    form button[type="submit"]:hover {
        background-color: #00BFFF; /* Slightly darker blue */
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

    td img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
    }
</style>


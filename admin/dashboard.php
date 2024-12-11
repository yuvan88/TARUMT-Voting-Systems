<?php
session_start();
include 'db/connection.php';
include 'header.php';

// Fetch all events
$sql = "SELECT * FROM events";
$events = mysqli_query($conn, $sql);

// Initialize variables
$candidates = [];
$totalVotes = 0;
$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
$event = null;

if ($eventId) {
    // Use prepared statement to fetch event details
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId); // "i" for integer
    $stmt->execute();
    $eventResult = $stmt->get_result();
    $event = $eventResult->fetch_assoc();
    $stmt->close();

    if ($event) {
        // Use prepared statement to fetch candidates and their votes
        $stmt = $conn->prepare("
            SELECT c.name, c.party, COUNT(v.candidate_id) AS vote_count
            FROM candidates c
            JOIN candidates_events ce ON c.id = ce.candidate_id
            LEFT JOIN votes v ON c.id = v.candidate_id
            WHERE ce.event_id = ?
            GROUP BY c.id
        ");
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $candidates[] = $row;
            $totalVotes += $row['vote_count'];
        }
        $stmt->close();
    } else {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
        }

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

        .sticky-header nav a {
            color: black;
            text-decoration: none;
            padding: 10px;
            transition: background-color 0.3s ease;
        }

        .sticky-header nav a:hover {
            background-color: #ddd;
        }

        .vote-percentage {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .event-card {
            width: 200px;
            text-align: center;
        }

        .event-poster {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
    <!-- Sticky Header Section -->
    <header class="sticky-header">
        <div class="header-container">
            <h1 class="logo">Vote System</h1>
            <nav>
                <a href="event_management.php">Manage Event</a>
                <a href="candidate_management.php">Manage Candidates</a>
                <a href="staff_management.php">Manage Staff</a>
                <a href="schedule_maintenance.php">Schedule Maintenance</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="vote-percentage">
        <h2>Vote Percentage</h2>

        <!-- Display list of events -->
        <div class="events">
            <h3>Select an Event</h3>
            <div class="event-list">
                <?php while ($eventData = mysqli_fetch_assoc($events)) { ?>
                    <div class="event-card">
                        <a href="dashboard.php?event_id=<?= htmlspecialchars($eventData['id']); ?>">
                            <img src="<?= htmlspecialchars($eventData['poster_image']); ?>"
                                alt="<?= htmlspecialchars($eventData['name']); ?>" class="event-poster">
                            <h4><?= htmlspecialchars($eventData['name']); ?></h4>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Display vote chart if an event is selected -->
        <?php if ($event && !empty($candidates)) { ?>
            <h3>Vote Distribution for <?= htmlspecialchars($event['name']); ?></h3>

            <div class="chart-container">
                <canvas id="voteChart" width="300" height="300"></canvas>
            </div>

            <script>
                const candidates = <?= json_encode($candidates); ?>;
                const candidateNames = candidates.map(candidate => candidate.name);
                const votes = candidates.map(candidate => candidate.vote_count);
                const totalVotes = <?= $totalVotes; ?>;

                // Calculate vote percentages
                const percentages = votes.map(vote => (vote / totalVotes) * 100);

                const ctx = document.getElementById('voteChart').getContext('2d');
                const voteChart = new Chart(ctx, {
                    type: 'pie',
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
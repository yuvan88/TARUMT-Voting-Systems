<section class="feedback" id="feedback">
    <h1 class="heading"> <span>Feedback</span> on Appointment </h1>
    <form action="submit_feedback.php" method="POST">
        <label for="rating">Rating:</label>
        <input type="number" name="rating" min="1" max="5" required><br>

        <label for="scheduling_efficiency">Scheduling Efficiency:</label>
        <input type="text" name="scheduling_efficiency" required><br>

        <label for="notification_effectiveness">Notification Effectiveness:</label>
        <input type="text" name="notification_effectiveness" required><br>

        <label for="overall_satisfaction">Overall Satisfaction:</label>
        <textarea name="overall_satisfaction" rows="4" required></textarea><br>

        <input type="submit" value="Submit Feedback" class="btn">
    </form>
</section>

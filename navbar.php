<nav>
        <ul>
            <li><a href="dashboard.php">Welness Tracker</a></li>
            <?php
            if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
                echo '<li><a href="dashboard.php">Home</a></li>';
                echo '<li><a href="update_profile.php">Profile</a></li>';
                echo '<li><a href="manage_water_logs.php">Water Logs</a></li>';
                echo '<li><a href="manage_exercise_logs.php">Exercise Logs</a></li>';
                echo '<li><a href="manage_sleep_logs.php">Sleep Logs</a></li>';
                echo '<li><a href="manage_mood_logs.php">Mood Logs</a></li>';
                echo '<li><a href="manage_reminders.php">Reminders</a></li>';
                echo '<li><a href="reports.php">Reports</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="register.php">Register</a></li>';
                echo '<li><a href="login.php">Login</a></li>';

            }
            ?>
        </ul>
    </nav>
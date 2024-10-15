<?php
session_start();

error_log("Ontvangen gegevens: " . print_r($_POST, true));

// Controleer of er al een CSRF-token is, anders genereer er een
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 64-tekens lange token
}
?>



<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoFort Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/nl.js"></script>
    <script src="dashboardscript.js"></script>
</head>
<body>

    <nav class="navbar">
        <h1>GeoFort Dashboard</h1>
        <ul class="navbar-list">
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <section class="welcome-section">
            <h2>Welkom op het Dashboard, <?php echo $_SESSION['username']; ?>!</h2>
            <p>Selecteer een week om de aanvragen te beheren.</p>
        </section>

        <!-- Periodekiezer -->
        <div id="date-picker">
            <label for="start_date">Startdatum:</label>
            <input type="date" id="start_date">
            <button id="submit-date">Bekijk aanvragen</button>
        </div>

        <section class="overview-section">
            <h3>Overzicht van Aanvragen</h3>
            <div id="requests-container"></div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 GeoFort</p>
    </footer>

</body>
</html>

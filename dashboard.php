<?php
session_start();

// Controleer of de gebruiker is ingelogd en of de sessie nog geldig is
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== "GeoFort Planner") {
    header("Location: inlog_pagina.php");
    exit();
}

// Stel inactiviteit op 15 minuten in (900 seconden)
$inactive = 900;

if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $inactive) {
        session_unset();
        session_destroy();
        echo json_encode(['success' => false, 'message' => 'Sessie verlopen door inactiviteit.']);
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time(); // Werk tijd van laatste activiteit bij
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
            <input type="text" id="start_date" >
            <button id="submit-date">Bekijk aanvragen</button>
        </div>

        <section class="overview-section">
            <h3>Overzicht van Aanvragen</h3>
            <div id="requests-container"></div>
            <button id="submit-statuses">Submit statussen</button>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 GeoFort</p>
    </footer>

</body>
</html>

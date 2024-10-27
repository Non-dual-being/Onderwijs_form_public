<?php
session_start();

// Controleer of de gebruiker is ingelogd en of de sessie nog geldig is
if (!isset($_SESSION['hidden_info']) || $_SESSION['hidden_info'] !== true || $_SESSION['hidden_info'] !== "hidden_info") {
    header("Location: Some_other_page.php");
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
<body class="body-dashboard">
    <header class="header-dashboard">
    <h1 class="Title_Dashboard">GeoFort Dashboard</h1>
    </header>
        <div id="content-container">
            <a class="meerInformatieToggle" id="Uitlog_Dashboard"><span>Uitloggen</span></a>
            <section class="welcome-section">
                <h2>Welkom op het Dashboard, <?php echo $_SESSION['Some_info']; ?>!</h2>
            </section>
            <fieldset>
            <legend class="dashboard-legend">Aanvragen per week</legend>
            <div id="date-picker">
                <label for="start_date">Agenda</label>
                <input type="text" id="start_date" >
                <button id="submit-date" name="weekaanvragen">Bekijk aanvragen voor de week</button>
            </div>
            
            <div class="meer-informatie-container">
                <a href="#" class="meerInformatieToggle" data-target="aanvragenInfo"><span>Meer informatie over het bekijken van de aanvragen</span></a>
                    <div id="aanvragenInfo" class="meerInformatieContent">
                        <p><strong class = "highlighted-text" >Datum kiezen:</strong> In de agenda klik je op de maandagen om de aanvragen van die week te zien.</p>
                        <p><strong class = "highlighted-text">Overzicht aavragen:</strong> In het overzicht kun je per aanvraag de status veranderen en de veranderingen doorgeven.</p>
                    </div>
                </div>
            </fieldset>
            <fieldset>
            <legend class="dashboard-legend">Aanvragen in optie</legend>
                <button id="submit-date" name="In optie">Bekijk alle in optie aanvragen</button>
                
                <p><div class="meer-informatie-container">
                <a href="#" class="meerInformatieToggle" data-target="aanvrageninoptieInfo"><span>Meer informatie over het bekijken van de aanvragen in optie</span></a>
                    <div id="aanvrageninoptieInfo" class="meerInformatieContent">
                        <p><strong class = "highlighted-text">Overzicht in optie aavragen:</strong> Klik op deze knop om alle opestaande in optie aanvragen te zien.</p>
                    </div>
                </div></p>
            </fieldset>

            <section class="overview-section-dashboard" id="overview-aanvragen-dashboard">
                <h3>Overzicht van aanvragen</h3>
                <div id="requests-container"></div>
                <button id="submit-statuses">Wijzigingen opslaan</button>
            </section>
        </div>

        <footer class="footer_dashboard">
    <p id="copy_logo">&copy; <span id="currentYear"></span> GeoFort</p>
    <script>
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>

    <script>
    document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
    <div class="footer-logo-container">
        <img src="images/geofort_logo.png" alt="GeoFort Logo" class="footer-logo">
    </div>
</footer>
</body>
</html>

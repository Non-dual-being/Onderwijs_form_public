<?php
header('Content-Type: application/json');

ini_set('display_errors', 0);  // Schakel weergave van fouten in de browser uit
ini_set('log_errors', 1);      // Log fouten naar een logbestand
error_reporting(E_ALL);        // Log alle fouten

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';

try {
    // Maak een verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL-query om de unieke weeknummers te verkrijgen van aanvragen met status 'In Optie' of 'Definitief'
    $sql = "
    SELECT DISTINCT WEEK(bezoekdatum, 3) AS weeknummer 
    FROM aanvragen
    WHERE status IN ('In Optie', 'Definitief')
    ORDER BY weeknummer ASC;

    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        error_log("dit is wat php opstuurt aan weeknummers" . $result);
        // Verstuur de unieke weeknummers naar de frontend
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Geen data gevonden']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
?>

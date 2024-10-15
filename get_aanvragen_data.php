<?php
header('Content-Type: application/json');

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

    // SQL-query om de minimum- en maximumdatum van aanvragen met status 'In Optie' of 'Definitief' op te halen
    $sql = "
        SELECT MIN(bezoekdatum) AS min_datum, MAX(bezoekdatum) AS max_datum
        FROM aanvragen
        WHERE status IN ('In Optie', 'Definitief');
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $minDatum = $result['min_datum'];
        $maxDatum = $result['max_datum'];

        // Bepaal de maandag van de week van de minimumdatum
        $startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($minDatum)));
        
        // Bepaal de vrijdag van de week van de maximumdatum
        $endOfWeek = date('Y-m-d', strtotime('friday this week', strtotime($maxDatum)));

        // Verstuur de datums als JSON naar de frontend
        echo json_encode([
            'min_datum' => $startOfWeek,
            'max_datum' => $endOfWeek
        ]);
    } else {
        echo json_encode(['error' => 'Geen data gevonden']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
?>


<?php
header('Content-Type: application/json');

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';

ini_set('display_errors', 0);  // Schakel weergave van fouten in de browser uit
ini_set('log_errors', 1);      // Log fouten naar een logbestand
error_reporting(E_ALL);        // Log alle fouten


try {
    // Maak een verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL-query om bezoekdata, aantal definitieve boekingen en totale leerlingen op te halen
    $sql = "
        SELECT bezoekdatum, SUM(aantal_leerlingen) AS totale_leerlingen, 
               COUNT(*) AS aantal_definitieve_boekingen
        FROM aanvragen
        WHERE status = 'Definitief'
        AND bezoekdatum BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 6 MONTH)
        GROUP BY bezoekdatum
        ORDER BY bezoekdatum;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatteer de output als een slim object
    $response = [];
    foreach ($data as $row) {
        $status = 'beschikbaar';

        if ($row['totale_leerlingen'] > 160 || $row['aantal_definitieve_boekingen'] > 2) {
            $status = 'onbeschikbaar';
            error_log("Fout: Te veel leerlingen of boekingen voor datum: {$row['bezoekdatum']}");
        } elseif ($row['totale_leerlingen'] == 160 || $row['aantal_definitieve_boekingen'] == 2) {
            $status = 'volgeboekt';
        } elseif (($row['aantal_definitieve_boekingen'] == 1 &&  $row['totale_leerlingen'] < 40)) {
            $status = 'volgeboekt';
        }elseif ($row['aantal_definitieve_boekingen'] == 1) {
            $status = 'beperkt beschikbaar';
        }
        
        $response[] = [
            'datum' => $row['bezoekdatum'],
            'totale_leerlingen' => $row['totale_leerlingen'],
            'status' => $status
        ];
    }

    // Stuur de data terug als JSON
    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
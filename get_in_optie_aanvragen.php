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

// Functie voor input validatie en ontsmetting
function sanitize_input($data, $maxLength, &$errors, $fieldName) {
    if (empty($data)) {
        $errors[$fieldName] = ucfirst($fieldName) . " mag niet leeg zijn.";
        return false;
    }

    $sanitizedData = htmlspecialchars(strip_tags(stripslashes(trim($data))));
    if (strlen($sanitizedData) > $maxLength) {
        $errors[$fieldName] = ucfirst($fieldName) . " mag niet langer zijn dan " . $maxLength . " tekens.";
        return false;
    }

    return $sanitizedData;
}

try {
    // Maak verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ontvang de status via POST
    $data = json_decode(file_get_contents('php://input'), true);
    $errors = [];
    $status = sanitize_input($data['status'] ?? '', 20, $errors, 'status');
    
    // Controleer of de ontvangen status 'In optie' is
    if ($status !== 'In optie') {
        echo json_encode(['success' => false, 'message' => 'Ongeldige status opgegeven.']);
        exit();
    }

    // Haal alle "In optie" aanvragen van het afgelopen jaar op
    $sql = "
    SELECT id, schoolnaam, aantal_leerlingen, status, bezoekdatum, email
    FROM aanvragen
    WHERE status = :status
      AND bezoekdatum BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 12 MONTH)
    ";

    error_log("Uitgevoerde SQL: $sql");

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => 'In optie']);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($requests)) {
        echo json_encode(['success' => true, 'message' => 'Geen "In optie" aanvragen gevonden voor het afgelopen jaar.']);
        error_log("Geen 'In optie' aanvragen gevonden in het afgelopen jaar.");
    } else {
        echo json_encode($requests);
        error_log("'In optie' aanvragen gevonden en doorgestuurd." . json_encode($requests));
    }

} catch (PDOException $e) {
    // Log de fout en stuur een foutmelding terug
    error_log('Databasefout: ' . $e->getMessage());
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
?>

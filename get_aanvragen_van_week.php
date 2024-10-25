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

    // Controleer of het een geldige datum is
    $datumObject = DateTime::createFromFormat('Y-m-d', $data);
    $datumErrors = DateTime::getLastErrors();
    if (!$datumObject || $datumErrors['warning_count'] > 0 || $datumErrors['error_count'] > 0) {
        $errors[$fieldName] = ucfirst($fieldName) . " is geen geldige datum.";
        error_log("Fout in datumvalidatie: " . print_r($datumErrors, true));
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

    // Ontvang de geselecteerde datum via POST
    $data = json_decode(file_get_contents('php://input'), true);
    $errors = [];
    $selectedDate = sanitize_input($data['selectedDate'] ?? '', 10, $errors, 'selectedDate');
    error_log("Geselecteerde datum: " . $selectedDate);

    if ($selectedDate === false) {
        // Verstuur de fout als de validatie mislukt
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit();
    }

    // Bepaal de maandag en zondag van de geselecteerde week
    $datumObject = new DateTime($selectedDate);
    $datumObject->modify('monday this week');
    $weekStart = $datumObject->format('Y-m-d');  // Formatteer als string voor SQL

    $datumObject->modify('sunday this week');
    $weekEnd = $datumObject->format('Y-m-d');  // Formatteer als string voor SQL

    // Log de weekbereik dat wordt gebruikt in de query
    error_log("Weekbereik: $weekStart - $weekEnd");

    // Haal alle aanvragen van de geselecteerde week op
    $sql = "
    SELECT id, schoolnaam, aantal_leerlingen, status, bezoekdatum, email
    FROM aanvragen
    WHERE bezoekdatum BETWEEN :weekStart AND :weekEnd
";

    error_log("Uitgevoerde SQL: $sql");
    error_log("weekStart: $weekStart, weekEnd: $weekEnd");

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':weekStart' => $weekStart, ':weekEnd' => $weekEnd]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($requests)) {
        echo json_encode(['success' => true, 'message' => 'Geen aanvragen gevonden voor de geselecteerde week.']);
        error_log("Geen aanvragen gevonden in de geselecteerde week.");
    } else {
        echo json_encode($requests);
        error_log("Aanvragen gevonden en doorgestuurd." . json_encode($requests));
    }

} catch (PDOException $e) {
    // Log de fout en stuur een foutmelding terug
    error_log('Databasefout: ' . $e->getMessage());
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
?>

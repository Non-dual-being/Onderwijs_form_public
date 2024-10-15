<?php
header('Content-Type: application/json');

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';

// Functie voor input validatie en ontsmetting
function sanitize_input($data, $maxLength, &$errors, $fieldName) {
    // Stap 1: Controleer of de input leeg is
    if (empty($data)) {
        $errors[$fieldName] = ucfirst($fieldName) . " mag niet leeg zijn.";
        return false;
    }

    // Stap 2: Controleer of het een geldige datum is
    $datumObject = DateTime::createFromFormat('Y-m-d', $data);
    $datumErrors = DateTime::getLastErrors();
    if (!$datumObject || $datumErrors['warning_count'] > 0 || $datumErrors['error_count'] > 0) {
        $errors[$fieldName] = ucfirst($fieldName) . " is geen geldige datum.";
        return false;
    }

    // Stap 3: Ontsmet de input
    $sanitizedData = htmlspecialchars(strip_tags(stripslashes(trim($data))));
    
    // Stap 4: Controleer de lengte van de input
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

    if ($selectedDate === false) {
        // Verzend de fout als de validatie mislukt
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit();
    }

    // Bepaal de maandag van de geselecteerde week
    $datumObject = new DateTime($selectedDate);
    $datumObject->modify('monday this week');
    $weekStart = $datumObject->format('Y-m-d');

    // Bepaal de zondag van dezelfde week
    $datumObject->modify('sunday this week');
    $weekEnd = $datumObject->format('Y-m-d');

    // Haal alle aanvragen van de geselecteerde week op
    $sql = "
        SELECT id, schoolnaam, aantal_leerlingen, status 
        FROM aanvragen
        WHERE bezoekdatum BETWEEN :weekStart AND :weekEnd
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':weekStart' => $weekStart, ':weekEnd' => $weekEnd]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Stuur de aanvragen terug naar de frontend
    echo json_encode($requests);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Databasefout: ' . $e->getMessage()]);
}
?>

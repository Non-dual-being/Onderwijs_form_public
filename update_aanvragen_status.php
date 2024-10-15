<?php
header('Content-Type: application/json');

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';

// Functie om invoer te ontsmetten
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Functie om de geldigheid van de status te controleren
function isValidStatus($status) {
    $validStatuses = ['In Optie', 'Definitief', 'Afgewezen'];
    return in_array($status, $validStatuses);
}

try {
    // Maak verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ontvang de updates via POST
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['updates']) || !is_array($data['updates'])) {
        echo json_encode(['success' => false, 'message' => 'Ongeldige data ontvangen']);
        exit();
    }

    $errors = [];
    $validUpdates = [];

    foreach ($data['updates'] as $update) {
        $id = filter_var($update['id'], FILTER_VALIDATE_INT);
        $status = sanitize_input($update['status']);

        if ($id === false) {
            $errors[] = "Ongeldige id: $id";
            continue;
        }

        if (!isValidStatus($status)) {
            $errors[] = "Ongeldige status: $status voor id: $id";
            continue;
        }

        // Controleer of de aanvraag met deze id bestaat in de database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM aanvragen WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            $errors[] = "Aanvraag met id $id bestaat niet";
            continue;
        }

        // Als alles geldig is, voeg het toe aan de lijst met geldige updates
        $validUpdates[] = ['id' => $id, 'status' => $status];
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit();
    }

    // Begin een transactie
    $pdo->beginTransaction();

    foreach ($validUpdates as $update) {
        // Werk de status bij in de database
        $sql = "UPDATE aanvragen SET status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':status' => $update['status'], ':id' => $update['id']]);
    }

    // Commit de transactie
    $pdo->commit();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Rol de transactie terug bij een fout
    $pdo->rollBack();
    error_log('Databasefout: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Databasefout: ' . $e->getMessage()]);
}

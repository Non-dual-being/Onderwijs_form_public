<?php
// Database verbinding (zorg ervoor dat dit naar je eigen settings verwijst)
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root';
$pass = '';
$port = '3307';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Functie om de onbeschikbare datums op te halen
function getUnavailableDates($pdo) {
    $stmt = $pdo->prepare("SELECT datum FROM onbeschikbare_data");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Functie om een aanvraag in de database op te slaan
function saveRequest($pdo, $data) {
    $stmt = $pdo->prepare("INSERT INTO aanvragen (schoolnaam, contactpersoon, email, telefoon, aantal_leerlingen, schooltype, status, bezoekdatum, opmerkingen)
                            VALUES (:schoolnaam, :contactpersoon, :email, :telefoon, :aantal_leerlingen, :schooltype, 'In optie', :bezoekdatum, :opmerkingen)");
    $stmt->execute($data);
    return $pdo->lastInsertId();
}

// Functie om e-mails te versturen
function sendEmail($to, $subject, $message) {
    // Stel je headers in en gebruik mail() of een externe email-service zoals PHPMailer of SwiftMailer
    // Hier een simpele mail() als voorbeeld:
    mail($to, $subject, $message);
}

// Verwerk het formulier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ontvang de aanvraaggegevens uit het formulier
    $schoolnaam = $_POST['schoolnaam'] ?? '';
    $contactpersoon = $_POST['contactpersoonvoornaam'] . ' ' . $_POST['contactpersoonachternaam'] ?? '';
    $email = $_POST['emailadres'] ?? '';
    $telefoon = $_POST['telefoonnummer'] ?? '';
    $aantal_leerlingen = (int)$_POST['aantalLeerlingen'] ?? 0;
    $schooltype = $_POST['onderwijsNiveau'] ?? '';
    $bezoekdatum = $_POST['bezoekdatum'] ?? '';
    $opmerkingen = $_POST['vragenOpmerkingen'] ?? '';

    // Validatie: controleer of de datum onbeschikbaar is
    $onbeschikbare_datums = getUnavailableDates($pdo);
    if (in_array($bezoekdatum, $onbeschikbare_datums)) {
        die(json_encode([
            'success' => false,
            'message' => 'De geselecteerde datum is niet beschikbaar. Kies een andere datum.'
        ]));
    }

    // Validatie: controleer het aantal leerlingen
    if ($aantal_leerlingen < 40 || $aantal_leerlingen > 160) {
        die(json_encode([
            'success' => false,
            'message' => 'Het aantal leerlingen moet tussen 40 en 160 liggen.'
        ]));
    }

    // Andere validaties kunnen hier worden toegevoegd...

    // Als de validaties slagen, sla de aanvraag op
    $data = [
        ':schoolnaam' => $schoolnaam,
        ':contactpersoon' => $contactpersoon,
        ':email' => $email,
        ':telefoon' => $telefoon,
        ':aantal_leerlingen' => $aantal_leerlingen,
        ':schooltype' => $schooltype,
        ':bezoekdatum' => $bezoekdatum,
        ':opmerkingen' => $opmerkingen
    ];

    $aanvraagId = saveRequest($pdo, $data);

    // E-mail de gebruiker en GeoFort
    $userMessage = "Bedankt voor je aanvraag! We nemen binnenkort contact met je op.";
    sendEmail($email, "Bevestiging van je aanvraag", $userMessage);

    $adminMessage = "Er is een nieuwe aanvraag binnengekomen van $contactpersoon.";
    sendEmail("geofort@onderwijs.nl", "Nieuwe aanvraag", $adminMessage);

    // Geef een succesmelding terug aan de frontend
    echo json_encode([
        'success' => true,
        'message' => 'De aanvraag is succesvol ontvangen!'
    ]);
}
?>

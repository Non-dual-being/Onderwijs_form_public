<?php
session_start();

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; // Of je databasegebruikersnaam
$pass = ''; // Je databasewachtwoord
$port = '3307';

header('Content-Type: application/json');  // Zorg ervoor dat de PHP-respons in JSON-formaat is

error_log("Ontvangen gegevens: " . print_r($_POST, true));

// Definieer de geldige waarden voor schooltype en keuzemodule
$onderwijsModules = [
    'primairOnderwijs' => [
        'standaard' => ["Klimaat-Experience", "Klimparcours", "Voedsel-Innovatie", "Dynamische-Globe"],
        'keuze' => ["Minecraft-Klimaatspeurtocht", "Earth-Watch"]
    ],
    'voortgezetOnderbouw' => [
        'standaard' => ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch"],
        'keuze' => ["Minecraft-Windenergiespeurtocht", "Stop-de-Klimaat-Klok"]
    ],
    'voortgezetBovenbouw' => [
        'standaard' => ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch", "Stop-de-Klimaat-Klok"],
        'keuze' => ["Crisismanagement"]
    ]
];

function generateDisabledDates() {
    $disabledDates = [];

    // Genereer weekends
    $currentDate = new DateTime('now');
    $endOfYearDate = new DateTime($currentDate->format('Y') . '-12-31');
    
    // Voeg weekenden toe
    while ($currentDate <= $endOfYearDate) {
        if (in_array($currentDate->format('N'), [6, 7])) {  // 6 = zaterdag, 7 = zondag
            $disabledDates[] = $currentDate->format('Y-m-d');
        }
        $currentDate->modify('+1 day');
    }

    // Voeg vakanties toe
    $schoolVacations = [
        ['start' => '2024-02-19', 'end' => '2024-02-23'],  // Voorjaarsvakantie
        ['start' => '2024-04-29', 'end' => '2024-05-03'],  // Meivakantie
        ['start' => '2024-07-15', 'end' => '2024-08-23'],  // Zomervakantie
        ['start' => '2024-10-21', 'end' => '2024-10-25'],  // Herfstvakantie
        ['start' => '2024-12-23', 'end' => '2024-12-31'],  // Kerstvakantie
    ];

    foreach ($schoolVacations as $vacation) {
        $start = new DateTime($vacation['start']);
        $end = new DateTime($vacation['end']);
        while ($start <= $end) {
            $disabledDates[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
    }

    return $disabledDates;
}


// Functie om een string te trimmen, te ontsmetten en te beperken tot een maximumlengte
function sanitize_input($data, $maxLength) {
    $data = htmlspecialchars(stripslashes(trim($data)));
    return substr($data, 0, $maxLength);  // Houd rekening met de maximale lengte
}

// Functie om de Nederlandse maandnamen naar Engelse maandnamen om te zetten
function convertDutchToEnglishDate($date) {
    $dutchMonths = [
        'januari', 'februari', 'maart', 'april', 'mei', 'juni', 
        'juli', 'augustus', 'september', 'oktober', 'november', 'december'
    ];
    
    $englishMonths = [
        'January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    return str_replace($dutchMonths, $englishMonths, $date);
}

try {
    // Maak een verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Valideer en ontsmet elk veld
    $errors = [];

    // Voornaam validatie
    if (empty($_POST['contactpersoonvoornaam']) || !preg_match("/^[A-Za-z\s.]*$/", $_POST['contactpersoonvoornaam'])) {
        $errors['contactpersoonvoornaam'] = "Ongeldige voornaam.";
    } else {
        $voornaam = sanitize_input($_POST['contactpersoonvoornaam'], 50);
    }

    // Achternaam validatie
    if (empty($_POST['contactpersoonachternaam']) || !preg_match("/^[A-Za-z\s]*$/", $_POST['contactpersoonachternaam'])) {
        $errors['contactpersoonachternaam'] = "Ongeldige achternaam.";
    } else {
        $achternaam = sanitize_input($_POST['contactpersoonachternaam'], 50);
    }

    // E-mail validatie
    if (empty($_POST['emailadres']) || !filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailadres'] = "Ongeldig e-mailadres.";
    } else {
        $email = sanitize_input($_POST['emailadres'], 100);
    }

    // Telefoonnummer validatie
    if (empty($_POST['telefoonnummer']) || !preg_match("/^(\+31|0)[1-9][0-9]{8}$/", $_POST['telefoonnummer'])) {
        $errors['telefoonnummer'] = "Ongeldig telefoonnummer.";
    } else {
        $telefoonnummer = sanitize_input($_POST['telefoonnummer'], 15);
    }

    // Aantal leerlingen validatie
    $aantalLeerlingen = (int) ($_POST['aantalLeerlingen'] ?? 0);
    if ($aantalLeerlingen < 40 || $aantalLeerlingen > 160) {
        $errors['aantalLeerlingen'] = "Aantal leerlingen moet tussen 40 en 160 zijn.";
    }

    // Bezoekdatum validatie
    if (empty($_POST['bezoekdatum'])) {
        $errors['bezoekdatum'] = "Bezoekdatum is verplicht.";
        error_log("Bezoekdatum is niet ingevuld.");
    } else {
        $ontvangenDatum = $_POST['bezoekdatum'];
        $ontvangenDatumEngels = convertDutchToEnglishDate($ontvangenDatum);
        $datumObject = DateTime::createFromFormat('d F Y', $ontvangenDatumEngels);

        if ($datumObject) {
            $bezoekdatum = $datumObject->format('Y-m-d');
            error_log("Geldige datum. Geconverteerd naar SQL-formaat: " . $bezoekdatum);

            // Controleer of de datum een disabled date is
            $disabledDates = generateDisabledDates();
            if (in_array($bezoekdatum, $disabledDates)) {
                $errors['bezoekdatum'] = "De gekozen datum is niet beschikbaar.";
                error_log("Gekozen datum is niet beschikbaar: " . $bezoekdatum);
            }
        } else {
            $errors['bezoekdatum'] = "Ongeldige datum geselecteerd.";
            error_log("Ongeldige datum ingevoerd: " . $_POST['bezoekdatum']);
        }
    }

    // Schoolnaam validatie
    if (empty($_POST['schoolnaam']) || !preg_match("/^[A-Za-z0-9\s.]+$/", $_POST['schoolnaam'])) {
        $errors['schoolnaam'] = "Ongeldige schoolnaam.";
    } else {
        $schoolnaam = sanitize_input($_POST['schoolnaam'], 80);
    }

    // Adres validatie
    if (empty($_POST['adres']) || !preg_match("/^[A-Za-z0-9\s.,'-]+$/", $_POST['adres'])) {
        $errors['adres'] = "Ongeldig adres.";
    } else {
        $adres = sanitize_input($_POST['adres'], 100);
    }

    // Postcode validatie
    if (empty($_POST['postcode']) || !preg_match("/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/", $_POST['postcode'])) {
        $errors['postcode'] = "Ongeldige postcode.";
    } else {
        $postcode = sanitize_input($_POST['postcode'], 7);
    }

    // Plaats validatie
    if (empty($_POST['plaats']) || !preg_match("/^[A-Za-z\s'-]+$/", $_POST['plaats'])) {
        $errors['plaats'] = "Ongeldige plaatsnaam.";
    } else {
        $plaats = sanitize_input($_POST['plaats'], 100);
    }

    // Keuze module validatie
    $keuzemodule = $_POST['keuzeModule'] ?? '';
    $schooltype = $_POST['onderwijsNiveau'] ?? '';
    if (!array_key_exists($schooltype, $onderwijsModules) || !in_array($keuzemodule, $onderwijsModules[$schooltype]['keuze'])) {
        $errors['keuzeModule'] = "Ongeldige keuzemodule.";
    } else {
        $keuze_module = sanitize_input($keuzemodule, 50);
    }

    // Check of er validatiefouten zijn
    if (!empty($errors)) {
        error_log("Validatiefouten: " . print_r($errors, true));
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit();
    }

    // SQL-query om te controleren op bestaande boekingen
    $sql = "
        SELECT SUM(aantal_leerlingen) AS totale_leerlingen, 
               COUNT(*) AS aantal_definitieve_boekingen
        FROM aanvragen
        WHERE status = 'Definitief' 
        AND bezoekdatum = :bezoekdatum
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':bezoekdatum' => $bezoekdatum]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer op boekingen en aantal leerlingen
    if ($result['aantal_definitieve_boekingen'] >= 2) {
        echo json_encode([
            'success' => false,
            'errors' => ['bezoekdatum' => 'Er zijn al 2 definitieve boekingen voor deze datum. Geen extra boekingen toegestaan.']
        ]);
        exit;
    }

    if ($result['aantal_definitieve_boekingen'] == 1 && ($result['totale_leerlingen'] + $aantalLeerlingen) > 160) {
        error_log("aantal leerlingen is teveel, namelijk: ". ($result['totale_leerlingen'] + $aantalLeerlingen) );
        echo json_encode([
            'success' => false,
            'errors' => ['aantal_leerlingen' => 'Het totale aantal leerlingen zou groter zijn dan 160 met deze boeking.']
        ]);
        exit;
    }

    // Voeg de aanvraag toe aan de database
    $sql = "INSERT INTO aanvragen (voornaam_contactpersoon, achternaam_contactpersoon, email, telefoon, aantal_leerlingen, bezoekdatum, schoolnaam, adres, postcode, plaats, keuze_module, status)
            VALUES (:voornaam, :achternaam, :email, :telefoonnummer, :aantal_leerlingen, :bezoekdatum, :schoolnaam, :adres, :postcode, :plaats, :keuze_module, 'Definitief')";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':voornaam' => $voornaam,
        ':achternaam' => $achternaam,
        ':email' => $email,
        ':telefoonnummer' => $telefoonnummer,
        ':aantal_leerlingen' => $aantalLeerlingen,
        ':bezoekdatum' => $bezoekdatum,
        ':schoolnaam' => $schoolnaam,
        ':adres' => $adres,
        ':postcode' => $postcode,
        ':plaats' => $plaats,
        ':keuze_module' => $keuze_module
    ]);

    // Succesvolle invoer
    echo json_encode([
        'success' => true,
        'message' => 'Aanvraag succesvol ontvangen!'
    ]);

} catch (PDOException $e) {
    // Foutafhandeling
    error_log("Databasefout: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'errors' => ['database' => 'Databasefout: ' . $e->getMessage()]
    ]);
}

?>

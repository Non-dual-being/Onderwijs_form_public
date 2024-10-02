<?php
session_start();

// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
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

    // Voeg weekenden en vakantiedata toe aan de lijst van niet-beschikbare data
    $currentDate = new DateTime('now');
    $endOfYearDate = new DateTime($currentDate->format('Y') . '-12-31');
    
    // Voeg weekenden toe
    while ($currentDate <= $endOfYearDate) {
        if (in_array($currentDate->format('N'), [6, 7])) {
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

function sanitize_input($data, $maxLength) {
    $data = htmlspecialchars(stripslashes(trim($data)));
    return substr($data, 0, $maxLength);
}

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



$remiseBreakAantal = isset($_POST['remiseBreakAantal']) ? (int) $_POST['remiseBreakAantal'] : 0;
$kazerneBreakAantal = isset($_POST['kazerneBreakAantal']) ? (int) $_POST['kazerneBreakAantal'] : 0;
$fortgrachtBreakAantal = isset($_POST['fortgrachtBreakAantal']) ? (int) $_POST['fortgrachtBreakAantal'] : 0;
$waterijsjeAantal = isset($_POST['waterijsjeAantal']) ? (int) $_POST['waterijsjeAantal'] : 0;
$pakjeDrinkenAantal = isset($_POST['pakjeDrinkenAantal']) ? (int) $_POST['pakjeDrinkenAantal'] : 0;
$remiseLunchAantal = isset($_POST['remiseLunchAantal']) ? (int) $_POST['remiseLunchAantal'] : 0;
$eigenPicknick = isset($_POST['eigenPicknickCheckbox']) ? 1 : 0;

// Log de waarden die zijn binnengekomen via het formulier
error_log("remiseBreakAantal: " . $remiseBreakAantal);
error_log("kazerneBreakAantal: " . $kazerneBreakAantal);
error_log("fortgrachtBreakAantal: " . $fortgrachtBreakAantal);
error_log("waterijsjeAantal: " . $waterijsjeAantal);
error_log("pakjeDrinkenAantal: " . $pakjeDrinkenAantal);
error_log("remiseLunchAantal: " . $remiseLunchAantal);
error_log("eigenPicknick: " . $eigenPicknick);

if (!is_int($remiseBreakAantal)) {
    error_log("remiseBreakAantal is geen integer: " . gettype($remiseBreakAantal));
}

if (!is_int($kazerneBreakAantal)) {
    error_log("remiseBreakAantal is geen integer: " . gettype($remiseBreakAantal));
}

try {
    // Maak verbinding met de database
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

    // Telefoonnummer validatie 1
    if (empty($_POST['schoolTelefoonnummer']) || !preg_match("/^(\+31|0)[1-9][0-9]{8}$/", $_POST['schoolTelefoonnummer'])) {
        $errors['schoolTelefoonnummer'] = "Ongeldig school-telefoonnummer.";
    } else {
        $schooltelefoonnummer = sanitize_input($_POST['schoolTelefoonnummer'], 15);
    }

     // Telefoonnummer validatie 1
     if (empty($_POST['contactTelefoonnummer']) || !preg_match("/^(\+31|0)[1-9][0-9]{8}$/", $_POST['contactTelefoonnummer'])) {
        $errors['contactTelefoonnummer'] = "Ongeldig contact-telefoonnummer.";
    } else {
        $contacttelefoonnummer = sanitize_input($_POST['contactTelefoonnummer'], 15);
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

   // Voor elke snack/lunch validatie met het juiste veldnaam in geval van fout

   $remiseBreakAantal=201;

if (!is_int($remiseBreakAantal) || $remiseBreakAantal < 0 || $remiseBreakAantal > 200) {
    $errors['remiseBreakAantal'] = "Ongeldig aantal voor Remise Break. Moet tussen 0 en 200 liggen.";
}
if (!is_int($kazerneBreakAantal) || $kazerneBreakAantal < 0 || $kazerneBreakAantal > 200) {
    $errors['kazerneBreakAantal'] = "Ongeldig aantal voor Kazerne Break. Moet tussen 0 en 200liggen.";
}
if (!is_int($fortgrachtBreakAantal) || $fortgrachtBreakAantal < 0 || $fortgrachtBreakAantal > 200) {
    $errors['fortgrachtBreakAantal'] = "Ongeldig aantal voor Fortgracht Break. Moet tussen 0 en 200 liggen.";
}
if (!is_int($waterijsjeAantal) || $waterijsjeAantal < 0 || $waterijsjeAantal > 200) {
    $errors['waterijsjeAantal'] = "Ongeldig aantal voor Waterijsje. Moet tussen 0 en 200 liggen.";
}
if (!is_int($pakjeDrinkenAantal) || $pakjeDrinkenAantal < 0 || $pakjeDrinkenAantal > 200) {
    $errors['pakjeDrinkenAantal'] = "Ongeldig aantal voor Pakje Drinken. Moet tussen 0 en 200 liggen.";
}


if (!is_int($remiseLunchAantal) || $remiseLunchAantal < 0 || $remiseLunchAantal > 200) {
    $errors['remiseLunchAantal'] = "Ongeldig aantal voor Remise Lunch. Moet tussen 0 en 200 liggen.";
}


if (!in_array($eigenPicknick, [0, 1], true)) {
    $errors['eigenPicknick'] = "Ongeldige waarde voor Eigen Picknick. Moet 0 of 1 zijn.";
}


   
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
        $maxMogelijk = 160 - $result['totale_leerlingen']; // Bereken het maximaal mogelijke aantal leerlingen
        error_log("Aantal leerlingen is teveel, namelijk: " . ($result['totale_leerlingen'] + $aantalLeerlingen));
        echo json_encode([
            'success' => false,
            'errors' => ['aantalLeerlingen' => "Er is voor {$aantalLeerlingen} leerlingen geboekt: maximaal {$maxMogelijk} mogelijk."]
        ]);
        exit;
    }

    // Voeg de aanvraag toe aan de database
// Voeg de aanvraag toe aan de database met snacks en lunches
$sql = "INSERT INTO aanvragen (
    voornaam_contactpersoon, 
    achternaam_contactpersoon, 
    email, 
    school_telefoon, 
    contact_telefoon, 
    aantal_leerlingen, 
    bezoekdatum, 
    schoolnaam, 
    adres, 
    postcode, 
    plaats, 
    keuze_module, 
    remise_break, 
    kazerne_break, 
    fortgracht_break, 
    waterijsje, 
    glas_limonade, 
    remise_lunch, 
    eigen_picknick, 
    status
) VALUES (
    :voornaam, 
    :achternaam, 
    :email, 
    :schooltelefoonnummer, 
    :contacttelefoonnummer, 
    :aantal_leerlingen, 
    :bezoekdatum, 
    :schoolnaam, 
    :adres, 
    :postcode, 
    :plaats, 
    :keuze_module, 
    :remiseBreakAantal, 
    :kazerneBreakAantal, 
    :fortgrachtBreakAantal, 
    :waterijsjeAantal, 
    :pakjeDrinkenAantal, 
    :remiseLunchAantal, 
    :eigenPicknick, 
    'Definitief'
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
':voornaam' => $voornaam,
':achternaam' => $achternaam,
':email' => $email,
':schooltelefoonnummer' => $schooltelefoonnummer,
':contacttelefoonnummer' => $contacttelefoonnummer,
':aantal_leerlingen' => $aantalLeerlingen,
':bezoekdatum' => $bezoekdatum,
':schoolnaam' => $schoolnaam,
':adres' => $adres,
':postcode' => $postcode,
':plaats' => $plaats,
':keuze_module' => $keuze_module,
':remiseBreakAantal' => $remiseBreakAantal,
':kazerneBreakAantal' => $kazerneBreakAantal,
':fortgrachtBreakAantal' => $fortgrachtBreakAantal,
':waterijsjeAantal' => $waterijsjeAantal,
':pakjeDrinkenAantal' => $pakjeDrinkenAantal,
':remiseLunchAantal' => $remiseLunchAantal,
':eigenPicknick' => $eigenPicknick
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

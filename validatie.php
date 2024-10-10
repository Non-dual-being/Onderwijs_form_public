<?php
session_start();

ini_set('display_errors', 0);  // Schakel weergave van fouten in de browser uit
ini_set('log_errors', 1);      // Log fouten naar een logbestand
error_reporting(E_ALL);        // Log alle fouten




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$smtp_password = getenv('SMTP_PASSWORD');




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
        'keuze' => ["Minecraft-Klimaatspeurtocht", "Earth-Watch","Stop-de-Klimaat-Klok"]
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

$schooltypeMapping = [
    'primairOnderwijs' => 'Primair Onderwijs',
    'voortgezetOnderbouw' => 'Voorgezet Onderwijs onderbouw',
    'voortgezetBovenbouw' => 'Voorgezet Onderwijs onderbouw'
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

function sanitize_input($data, $maxLength, &$errors, $fieldName) {
    $sanitizedData = htmlspecialchars(stripslashes(trim($data)));
    
    if (strlen($sanitizedData) > $maxLength) {
        $errors[$fieldName] = ucfirst($fieldName) . " mag niet langer zijn dan " . $maxLength . " tekens.";
        return false;
    }
    
    return $sanitizedData;
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
$eigenPicknick = isset($_POST['eigenPicknick']) && ($_POST['eigenPicknick'] == 1 || $_POST['eigenPicknick'] == 0) ? (int)$_POST['eigenPicknick'] : 0;

$foodPrice = null; // Initialiseer de variabele
if (isset($_POST['foodPrice'])) {
    $tempPrice = (float)$_POST['foodPrice'];

    // Check of de waarde binnen het bereik ligt
    if ($tempPrice > 0 && $tempPrice < 4000) {
        $foodPrice = $tempPrice; // Alleen toewijzen als de waarde voldoet aan de voorwaarden
    }
}


$bezoekPrice = null; // Initialiseer de variabele
if (isset($_POST['bezoekPrice'])) {
    $tempPrice = (float)$_POST['bezoekPrice'];
    
    // Check of de waarde binnen het bereik ligt
    if ($tempPrice > 0 && $tempPrice < 4000) {
        $bezoekPrice = $tempPrice; // Alleen toewijzen als de waarde voldoet aan de voorwaarden
    }
}

$totalPrice = null; // Initialiseer de variabele
if (isset($_POST['totalPrice'])) {
    $tempPrice = (float)$_POST['totalPrice'];
    
    // Check of de waarde binnen het bereik ligt
    if ($tempPrice > 0 && $tempPrice < 8000) {
        $totalPrice = $tempPrice; // Alleen toewijzen als de waarde voldoet aan de voorwaarden
    }
}


try {
    // Maak verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Begin een transactie (voorkomen dat er gemaild wordt als db een fout geeft)
    $pdo->beginTransaction();

    // Valideer en ontsmet elk veld
    $errors = [];
    error_log("Validatie van invoervelden gestart...");

    // Voornaam validatie
    if (empty($_POST['contactpersoonvoornaam']) || !preg_match("/^[A-Za-z\s.]*$/", $_POST['contactpersoonvoornaam'])) {
        $errors['contactpersoonvoornaam'] = "Ongeldige voornaam.";
    } else {
        $voornaam = sanitize_input($_POST['contactpersoonvoornaam'], 50, $errors, 'voornaam');
    }

    // Achternaam validatie
    if (empty($_POST['contactpersoonachternaam']) || !preg_match("/^[A-Za-z\s]*$/", $_POST['contactpersoonachternaam'])) {
        $errors['contactpersoonachternaam'] = "Ongeldige achternaam.";
    } else {
        $achternaam = sanitize_input($_POST['contactpersoonachternaam'], 50, $errors, 'achternaam');
    }

    // E-mail validatie
    if (empty($_POST['emailadres']) || !filter_var($_POST['emailadres'], FILTER_VALIDATE_EMAIL)) {
        $errors['emailadres'] = "Ongeldig e-mailadres.";
    } else {
        $email = sanitize_input($_POST['emailadres'], 100, $errors, 'email');
    }

    // Telefoonnummer validatie 1
    if (empty($_POST['schoolTelefoonnummer']) || !preg_match("/^(\+31|0)[1-9][0-9]{8}$/", $_POST['schoolTelefoonnummer'])) {
        $errors['schoolTelefoonnummer'] = "Ongeldig school-telefoonnummer.";
    } else {
        $schooltelefoonnummer = sanitize_input($_POST['schoolTelefoonnummer'], 15, $errors, 'schooltelefoonnummer');
    }

     // Telefoonnummer validatie 1
     if (empty($_POST['contactTelefoonnummer']) || !preg_match("/^(\+31|0)[1-9][0-9]{8}$/", $_POST['contactTelefoonnummer'])) {
        $errors['contactTelefoonnummer'] = "Ongeldig contact-telefoonnummer.";
    } else {
        $contacttelefoonnummer = sanitize_input($_POST['contactTelefoonnummer'], 15, $errors,'contactTelefoonnummer' );
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
           
            // Controleer of de datum een disabled date is
            $disabledDates = generateDisabledDates();
            if (in_array($bezoekdatum, $disabledDates)) {
                $errors['bezoekdatum'] = "De gekozen datum is niet beschikbaar.";
                
            }
        } else {
            $errors['bezoekdatum'] = "Ongeldige datum geselecteerd.";
            
        }
    }

    // Schoolnaam validatie
    if (empty($_POST['schoolnaam']) || !preg_match("/^[A-Za-z0-9\s.]+$/", $_POST['schoolnaam'])) {
        $errors['schoolnaam'] = "Ongeldige schoolnaam.";
    } else {
        $schoolnaam = sanitize_input($_POST['schoolnaam'], 80, $errors, 'schoolnaam');
    }

    // Adres validatie
    if (empty($_POST['adres']) || !preg_match("/^[A-Za-z0-9\s.,'-]+$/", $_POST['adres'])) {
        $errors['adres'] = "Ongeldig adres.";
    } else {
        $adres = sanitize_input($_POST['adres'], 100, $errors, 'adres');
    }

    // Postcode validatie
    if (empty($_POST['postcode']) || !preg_match("/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/", $_POST['postcode'])) {
        $errors['postcode'] = "Ongeldige postcode.";
    } else {
        $postcode = sanitize_input($_POST['postcode'], 7, $errors, 'postcode');
    }

    // Plaats validatie
    if (empty($_POST['plaats']) || !preg_match("/^[A-Za-z\s'-]+$/", $_POST['plaats'])) {
        $errors['plaats'] = "Ongeldige plaatsnaam.";
    } else {
        $plaats = sanitize_input($_POST['plaats'], 100, $errors, 'plaats');
    }

    // Keuze module validatie
    $keuzemodule = $_POST['keuzeModule'] ?? '';
    $schooltype = $_POST['onderwijsNiveau'] ?? '';
    if (!array_key_exists($schooltype, $onderwijsModules) || !in_array($keuzemodule, $onderwijsModules[$schooltype]['keuze'])) {
        $errors['keuzeModule'] = "Ongeldige keuzemodule.";
    } else {
        $keuze_module = sanitize_input($_POST['keuzeModule'], 50, $errors, 'keuzeModule');
        $schooltype = sanitize_input($_POST['onderwijsNiveau'], 50, $errors, 'onderwijsNiveau');
    }

   // Voor elke snack/lunch validatie met het juiste veldnaam in geval van fout

    if (!is_int($remiseBreakAantal) || $remiseBreakAantal < 0 || $remiseBreakAantal > 200) {
        $errors['remiseBreakAantal'] = "Ongeldig aantal voor Remise Break. Moet tussen 0 en 200 liggen.";
    }
    if (!is_int($kazerneBreakAantal) || $kazerneBreakAantal < 0 || $kazerneBreakAantal > 200) {
        $errors['kazerneBreakAantal'] = "Ongeldig aantal voor Kazerne Break. Moet tussen 0 en 200 liggen.";
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
    error_log("Validatie geslaagd, doorgaan met SQL-query's...");

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
        exit();
    }

    if ($result['aantal_definitieve_boekingen'] == 1 && ($result['totale_leerlingen'] + $aantalLeerlingen) > 160) {
        $maxMogelijk = 160 - $result['totale_leerlingen']; // Bereken het maximaal mogelijke aantal leerlingen
        error_log("Aantal leerlingen is teveel, namelijk: " . ($result['totale_leerlingen'] + $aantalLeerlingen));
        echo json_encode([
            'success' => false,
            'errors' => ['aantalLeerlingen' => "Er is voor {$aantalLeerlingen} leerlingen geboekt: maximaal {$maxMogelijk} mogelijk."]
        ]);
        exit();
    }

    // Voeg de aanvraag toe aan de database
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

     // Commit de transactie
    if ($pdo->commit()){
        $mail = new PHPMailer(true);
    try {
        // Configuratie voor het gebruik van SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kevin@geofort.nl';
        $mail->Password = 'Mah67312';  // Zorg ervoor dat wachtwoorden veilig worden opgeslagen en niet hardcoded
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPDebug = 0; // Toon SMTP-fouten en communicatie in de log

        $schooltype = $schooltypeMapping[$schooltype];
       

        setlocale(LC_TIME, 'nl_NL.UTF-8');

        // Maak een DateTime-object aan
        $dateTime = new DateTime($bezoekdatum);

        // Formatter voor Nederlandse datuminstellingen
        $fmt = new IntlDateFormatter('nl_NL', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Europe/Amsterdam', IntlDateFormatter::GREGORIAN, 'EEEE d MMMM Y');

        // Datum omzetten naar het gewenste formaat
        $nederlandseDatum = $fmt->format($dateTime);
    
        // Afzender en ontvangers
        $mail->setFrom('kevin@geofort.nl', 'GeoFort Onderwijs');
        $mail->addAddress($email, $voornaam . ' ' . $achternaam);  // Ontvanger
        //$mail->addBCC('onderwijs@geofort.nl');  // Blind Carbon Copy
        $mail->addBCC('kevin@geofort.nl');  // Zelf een BCC ontvangen


    
        // Zet het formaat van de e-mail op HTML
        $mail->isHTML(true);
        $mail->Subject = 'Bevestiging aanvraag schoolbezoek GeoFort';
    
        // Inhoud van de e-mail (HTML)
        $mailContent = "
        <p>Beste " . htmlspecialchars($voornaam) . ",<br></p>
        <p>Bedankt voor uw aanvraag voor een schoolbezoek aan het GeoFort.</p>
        <p>Hieronder het overzicht van uw aanvraag:</p>
        
        <h4 style='margin-bottom: 0; padding: 0; text-decoration: underline'>Algemene gegevens</h4>
        <ul style='font-size: 12px;'>  <!-- Kleinere tekst -->
            <li><strong>Voornaam:</strong> " . htmlspecialchars($voornaam) . "</li>
            <li><strong>Achternaam:</strong> " . htmlspecialchars($achternaam) . "</li>
            <li><strong>Email:</strong> " . htmlspecialchars($email) . "</li>
            <li><strong>Schooltelefoon:</strong> " . htmlspecialchars($schooltelefoonnummer) . "</li>
            <li><strong>Telefoon contactpersoon:</strong> " . htmlspecialchars($contacttelefoonnummer) . "</li>
            <li><strong>Schoolnaam:</strong> " . htmlspecialchars($schoolnaam) . "</li>
            <li><strong>Adres:</strong> " . htmlspecialchars($adres) . "</li>
            <li><strong>Postcode:</strong> " . htmlspecialchars($postcode) . "</li>
            <li><strong>Plaats:</strong> " . htmlspecialchars($plaats) . "<br></li>
        </ul>  
        
        <h4 style='margin: 0; padding: 0; text-decoration: underline'>Bezoekgegevens</h4>
        <ul style='font-size: 12px;'>
            <li><strong>Aantal leerlingen:</strong> " . htmlspecialchars($aantalLeerlingen) . "</li>
            <li><strong>Bezoekdatum:</strong> " . htmlspecialchars($nederlandseDatum) . "</li>
            <li><strong>Schooltype:</strong> " . htmlspecialchars($schooltype) . "</li>
            <li><strong>Keuze-module:</strong> " . htmlspecialchars($keuze_module) . "</li>
        <br></ul>
    ";
    $etenDrinken = [];
if ($remiseBreakAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Remise Break snacks: </strong>" . htmlspecialchars($remiseBreakAantal);
}
if ($kazerneBreakAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Kazerne Break snacks: </strong>" . htmlspecialchars($kazerneBreakAantal);
}
if ($fortgrachtBreakAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Fortgracht Break snacks: </strong>" . htmlspecialchars($fortgrachtBreakAantal);
}
if ($waterijsjeAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Waterijsjes: </strong>" . htmlspecialchars($waterijsjeAantal);
}
if ($pakjeDrinkenAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Pakjes Drinken: </strong>" . htmlspecialchars($pakjeDrinkenAantal);
}
if ($remiseLunchAantal > 0) {
    $etenDrinken[] = "<strong>Aantal Remise Lunches: </strong>" . htmlspecialchars($remiseLunchAantal);
}

if ($eigenPicknick == 1)  {
    $etenDrinken[] = "<strong>Eigen Picknick: </strong> Ja";

}elseif($eigenPicknick== 0) {
    $etenDrinken[] = "<strong>Eigen Picknick: </strong> Nee";
}

// Controleer of er iets besteld is en voeg dit toe aan de e-mail
if (!empty($etenDrinken)) {
    $mailContent .= "<h4 style='margin: 0; padding: 0; text-decoration: underline'><strong>Eten en drinken</strong></h4>";
    $mailContent .= "<ul style='font-size: 12px;'>";
    foreach ($etenDrinken as $item) {
        $mailContent .= "<li>" . $item . "</li>"; // verwijder htmlspecialchars omdat de item al veilig is
    }
    $mailContent .= "<br></ul>";
}

    
    $mailContent .= "
    <p>Hieronder vindt u verder het prijsoverzicht van het geplande bezoek aan GeoFort. Mocht er iets niet kloppen in uw aanvraag of mocht u nog vragen hebben, aarzel dan niet om contact met ons op te nemen. Wij helpen u graag verder.<br></p>";


    // Prijsoverzicht
    $mailContent .= "
    
    <h4 style='margin: 0; padding: 0; text-decoration: underline'>Prijsoverzicht</h4>
    <ul style='font-size: 12px;'>
    <li style='font-size: 12px;'><strong>Prijs voor het bezoek:</strong> " . number_format($bezoekPrice, 2, ',', '.') . " euro</li>
    <li style='font-size: 12px;'><strong>Prijs voor het bestelde eten en drinken:</strong> " . number_format($foodPrice, 2, ',', '.') . " euro<br></li>
    </ul>
    <p style='font-size: 14px;'><strong>Totale prijs voor het bezoek: <span style='text-decoration: underline;'> " . number_format($totalPrice, 2, ',', '.') . " euro </span></strong><br></p>
    
    ";
    

    $mailContent .= "
        <p>We kijken ernaar uit om u binnenkort te mogen verwelkomen op GeoFort.<br></p>
        <p>Met vriendelijke groet,<br></p>
        <p>Het GeoFort Onderwijs Team</p>
    ";

    
        // Stel de e-mail body in
        $mail->Body = $mailContent;
    
        // Voor niet-HTML clients stel je een alternatieve tekstversie in
        $mail->AltBody = strip_tags($mailContent);
    
        // Verstuur de e-mail
        $mail->send();
    
        // JSON response naar de client
        $response = json_encode(['success' => true, 'message' => 'Aanvraag succesvol ontvangen!']);
        
    } catch (Exception $e) {
        // Fout bij het versturen van de e-mail
        error_log("Emailfout: " . $e->getMessage());
        $response = json_encode(['success' => false, 'servererror' => 'Er is een fout opgetreden bij verwerken van de aanvraag']);
    }
    

    }

    } catch (PDOException $e) {
        // Foutafhandeling bij databaseproblemen
        error_log("Databasefout: " . $e->getMessage());


        if ($pdo) {
            $pdo->rollBack();  // Alleen rollback als de verbinding succesvol was
        }

        $response = json_encode([
            'success' => false,
            'servererror' => 'Er is een fout opgetreden bij het verwerken van de aanvraag!'
        ]);

        error_log("de response" . $response);   
    }
    echo $response;
    exit();
?>

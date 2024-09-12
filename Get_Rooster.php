<?php
// Database inloggegevens
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; // of je databasegebruikersnaam
$pass = ''; // je databasewachtwoord
$port = '3307';

// Definieer de geldige waarden voor schooltype en lesmodule
$onderwijsModules = [
    'primairOnderwijs' => [
        'standaard' => ["Klimaat-Experience", "Klimparcours", "Voedsel-Innovatie", "Dynamische-Globe"],
        'keuze' => ["Minecraft-klimaatspeurtocht", "Earth-Watch"]
    ],
    'voortgezetOnderbouw' => [
        'standaard' => ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch"],
        'keuze' => ["Minecraft-windenergiespeurtocht", "Stop-de-Klimaat-Klok"]
    ],
    'voortgezetBovenbouw' => [
        'standaard' => ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch", "Stop-de-Klimaat-Klok"],
        'keuze' => ["Crisismanagement"]
    ]
];

try {
    // Maak een verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verkrijg parameters van het AJAX-verzoek
    $schooltype = $_POST['schooltype'] ?? '';
    $lesmodule = $_POST['lesmodule'] ?? '';
    $aantalLeerlingen = (int)($_POST['aantalleerlingen'] ?? 0); // Cast naar integer

    error_log("Schooltype: " . $schooltype);
    error_log("Lesmodule: " . $lesmodule);
    error_log("Aantal Leerlingen: " . $aantalLeerlingen);

    // Validatie van aantal leerlingen
    if ($aantalLeerlingen < 40 || $aantalLeerlingen > 160) {
        echo json_encode([
            'success' => false,
            'message' => 'Aantal leerlingen moet tussen 40 en 160 liggen.'
        ]);
        exit;
    }

    // Validatie van schooltype
    if (!array_key_exists($schooltype, $onderwijsModules)) {
        echo json_encode([
            'success' => false,
            'message' => 'Ongeldig schooltype.'
        ]);
        exit;
    }

    // Validatie van lesmodule
    $isValidModule = in_array($lesmodule, $onderwijsModules[$schooltype]['keuze']);
    if (!$isValidModule) {
        echo json_encode([
            'success' => false,
            'message' => 'Ongeldige lesmodule.'
        ]);
        exit;
    }

    // Mapping van schooltype voor database
    $schooltypeMapping = [
        'primairOnderwijs' => 'primair',
        'voortgezetOnderbouw' => 'onderbouw',
        'voortgezetBovenbouw' => 'bovenbouw'
    ];

    // Mapping van schooltype naar de vereiste waarde
    if (isset($schooltypeMapping[$schooltype])) {
        $schooltype = $schooltypeMapping[$schooltype];
    }

    // Vervang streepjes in lesmodule door spaties
    $lesmodule = str_replace('-', ' ', $lesmodule);

    // Voorbereid SQL-commando om de afbeelding op te halen
    $sql = "SELECT afbeelding FROM roosters 
            WHERE schooltype = :schooltype 
            AND lesmodule = :lesmodule 
            AND leerlingen_min <= :aantalLeerlingen 
            AND leerlingen_max >= :aantalLeerlingen";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':schooltype', $schooltype);
    $stmt->bindParam(':lesmodule', $lesmodule);
    $stmt->bindParam(':aantalLeerlingen', $aantalLeerlingen, PDO::PARAM_INT); // Bind als integer
    $stmt->execute();

    $rooster = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rooster) {
        // Verwijder 'images/' uit het pad en vervang .png met .pdf
        $afbeeldingZonderImages = str_replace('images/', '', $rooster['afbeelding']);
        $pdfFileName = str_replace('.png', '.pdf', $afbeeldingZonderImages);
        $pdfFilePath = 'Roosters/' . $pdfFileName;  // Het volledige pad naar de PDF
    
        // Stuur zowel de afbeelding als het PDF-bestandspad terug in JSON-formaat
        echo json_encode([
            'success' => true,
            'afbeelding' => $rooster['afbeelding'], // Afbeelding zoals in de database
            'pdf' => $pdfFilePath                   // Correcte PDF-bestandspad
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Geen rooster gevonden voor de opgegeven criteria.'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Databasefout: ' . $e->getMessage()
    ]);
}
?>

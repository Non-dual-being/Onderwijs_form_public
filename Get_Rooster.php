<?php
// Database inloggegevens
$host = 'hidden_info';
$dbname = 'hidden_info';
$user = 'hidden_info'; 
$pass = 'hidden_info'; 
$port = 'hidden_info';

// Definieer de geldige waarden voor schooltype en keuzemodule
$onderwijsModules = [
    'primairOnderwijs' => [
        'standaard' => ["Klimaat-Experience", "Klimparcours", "Voedsel-Innovatie", "Dynamische-Globe"],
        'keuze' => ["Minecraft-Klimaatspeurtocht", "Earth-Watch","Stop-de-Klimaat-Klok"]
    ],
    'voortgezetOnderbouw' => [
        'standaard' => ["Klimaat-Experience", "Voedsel-Innovatie", "Dynamische-Globe", "Earth-Watch"],
        'keuze' => ["Minecraft-Windenergiespeurtocht", "Stop-de-Klimaat-Klok","Minecraft-Programmeren"]
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
    $keuzemodule = $_POST['lesmodule'] ?? '';
    $aantalLeerlingen = (int)($_POST['aantalleerlingen'] ?? 0); // Cast naar integer

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

    // Validatie van keuzemodule
    $isValidModule = in_array($keuzemodule, $onderwijsModules[$schooltype]['keuze']);
    if (!$isValidModule) {
        echo json_encode([
            'success' => false,
            'message' => 'Ongeldige keuzemodule.'
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

    // Voorbereid SQL-commando om de afbeelding op te halen
    $sql = "SELECT afbeelding FROM roosters 
            WHERE schooltype = :schooltype 
            AND keuzemodule = :keuzemodule 
            AND leerlingen_min <= :aantalLeerlingen 
            AND leerlingen_max >= :aantalLeerlingen";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':schooltype', $schooltype);
    $stmt->bindParam(':keuzemodule', $keuzemodule);
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

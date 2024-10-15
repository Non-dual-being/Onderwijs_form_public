<?php
session_start();
header('Content-Type: application/json');  
require 'vendor/autoload.php';
ini_set('display_errors', 0); 
ini_set('log_errors', 1);      
error_reporting(E_ALL); 

$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';

// Maximaal aantal toegestane mislukte pogingen
$maxAttempts = 3;


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false]);
    error_log("Ongeldige csrf_token");
    exit();
}



// Functie om input te ontsmetten
function sanitize_input($data, $maxLength, &$errors, $fieldName) {
    $sanitizedData = htmlspecialchars(strip_tags(stripslashes(trim($data))));
    if (strlen($sanitizedData) > $maxLength) {
        $errors[$fieldName] = ucfirst($fieldName) . " mag niet langer zijn dan " . $maxLength . " tekens.";
        return false;
    }
    return $sanitizedData;
}

// Functie om het IP-adres op te halen
function get_ip_address() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ip_address = get_ip_address();

try {

 //Maak verbinding met de database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $errors = [];

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Ongeldig e-mailadres.";
    } else {
        $email = sanitize_input($_POST['email'], 50, $errors, 'email');
        error_log("email" . $email);
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Voer een wachtwoord in.";
    } else {
        $password = sanitize_input($_POST['password'], 50, $errors, 'password');
        error_log("Password" .$password);
    }
    

    if (!empty($errors)) {
        echo json_encode([
            'success' => false
        ]);
        exit();
    }
    //

    // Controleer of het IP-adres geblokkeerd is
    $stmt = $pdo->prepare("SELECT blocked FROM login_attempts WHERE ip_address = :ip_address");
    $stmt->execute([':ip_address' => $ip_address]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['blocked'] == 1) {
        echo json_encode([
            'success' => false
        ]);
        exit();
    }

    // Controleer op mislukte pogingen binnen 1 uur
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = :ip_address AND attempt_time > (NOW() - INTERVAL 1 HOUR)");
    $stmt->execute([':ip_address' => $ip_address]);
    $attempt_count = $stmt->fetchColumn();

    if ($attempt_count >= $maxAttempts) {
        // Blokkeer het IP-adres
        $stmt = $pdo->prepare("UPDATE login_attempts SET blocked = 1 WHERE ip_address = :ip_address");
        $stmt->execute([':ip_address' => $ip_address]);

        error_log("IP adress geblokt na mislukt pogingen: " . $ip_address);


        echo json_encode([
            'success' => false
        ]);
        exit();
    }

    // Validatie van e-mail en wachtwoord
   

    // Haal de gebruiker op uit de database
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        
        // Sessie-ID regenereren voor beveiliging
        session_regenerate_id(true);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        $_SESSION['username'] = "GeoFort Planner";

        $_SESSION['loggedin'] = true;
        echo json_encode(['success' => true]);

        // Verwijder mislukte pogingen na succesvolle inlog
        $stmt = $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = :ip_address");
        $stmt->execute([':ip_address' => $ip_address]);

    } else {
        // Log de mislukte poging
        $stmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (:ip_address, NOW())");
        $stmt->execute([':ip_address' => $ip_address]);

        error_log("IP adress toegevoegd na mislukte poging: " . $ip_address);

        echo json_encode(['success' => false]);
        exit();
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Serverfout bij inloggen.']);
    error_log("Fout tijdens inloggen: " . $e->getMessage());
    exit();
}
?>

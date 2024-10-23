<?php
session_start();

// Controleer of er al een CSRF-token is, anders genereer er een
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 64-tekens lange token
}
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoFort Inlog Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <script src="inlog.js"></script>
</head>
<body >
<form method="POST" class="login-form" id="login-form">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Email" required>
    
    <label for="password">Wachtwoord</label>
    <input type="password" id="password" name="password" placeholder="Wachtwoord" required>
    
    <button type="submit" id="verzendknop" class="submit-button">Inloggen</button>
</form>
</body>
</html>


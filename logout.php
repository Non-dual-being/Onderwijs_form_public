<?php
session_start();
session_unset();    // Verwijdert alle sessievariabelen
session_destroy();  // Vernietig de sessie
header("Location: Some_page.php");  // Stuur de gebruiker terug naar de inlogpagina
exit();
?>

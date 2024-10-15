<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['loggedin'])) {
    header("Location: inlog_pagina.php");
    exit();
}

// Verbinding maken met de database
$host = '127.0.0.1';
$dbname = 'school_db';
$user = 'root'; 
$pass = ''; 
$port = '3307';
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['status'] as $id => $status) {
        $stmt = $pdo->prepare("UPDATE aanvragen SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    header("Location: dashboard.php");
}
?>

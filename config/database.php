<?php
// Configuration des paramètres de connexion à la base de données
$host = 'localhost';         // Hôte de la base de données
$dbname = 'task_manager';    // Nom de la base de données
$db_username = 'root'; // Nom d'utilisateur pour la base de données
$db_password = ''; // Mot de passe pour la base de données

// DSN (Data Source Name) pour la connexion PDO
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Tentative de connexion à la base de données
try {
    $pdo = new PDO($dsn, $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données: " . $e->getMessage());
}

return $pdo;
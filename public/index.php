<?php
session_start();

// Inclusion du gestionnaire d'authentification et de la base de données
require_once '../config/database.php';
require_once '../src/Auth.php';

// Création de l'instance de connexion à la base de données
$pdo = require '../config/database.php';

// Vérification si l'utilisateur est déjà connecté
if (Auth::check()) {
    header('Location: dashboard.php');
    exit;
}

// Gestion du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    if (Auth::attempt($pdo, $username_or_email, $password)) {
        // Si les identifiants sont corrects, redirection vers le tableau de bord
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = "Identifiants incorrects, veuillez réessayer.";
    }
}

// Inclusion du template d'en-tête
include '../templates/header.php';
?>

<div class="container">
    <h2>Bienvenue dans le gestionnaire de tâches</h2>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="index.php" method="POST">
        Username or Email: <input type="text" name="username_or_email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Register</a>
</div>
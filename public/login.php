<?php
session_start();

// Inclusion du gestionnaire de base de données et du gestionnaire d'authentification
require_once '../config/database.php';
require_once '../src/Auth.php';

// Création de l'instance de connexion à la base de données
$pdo = require '../config/database.php';

// Vérification si l'utilisateur est déjà connecté
if (Auth::check()) {
    header('Location: dashboard.php');
    exit;
}

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Tentative de connexion en utilisant les données fournies
    if (Auth::attempt($pdo, $username_or_email, $password)) {
        // Redirection vers le tableau de bord si la connexion est réussie
        header('Location: dashboard.php');
        exit;
    } else {
        // Message d'erreur si les identifiants sont incorrects
        $error = 'Identifiants incorrects. Veuillez réessayer.';
    }
}

// Inclusion du template d'en-tête
include '../templates/header.php';
?>

<div class="container">
    <h2>Connexion</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        Username or Email: <input type="text" name="username_or_email" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Pas encore inscrit ? S'inscrire ici</a>
</div>
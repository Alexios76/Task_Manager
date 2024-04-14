<?php
session_start();

// Inclusion du gestionnaire de base de données
require_once '../config/database.php';

// Création de l'instance de connexion à la base de données
$pdo = require '../config/database.php';

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation des entrées
    $errors = [];
    if (empty($username)) {
        $errors['username'] = 'Le nom d\'utilisateur est requis.';
    }
    if (empty($email)) {
        $errors['email'] = 'L\'email est requis.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L\'email n\'est pas valide.';
    }
    if (empty($password)) {
        $errors['password'] = 'Le mot de passe est requis.';
    }

    // Vérification si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $errors['general'] = 'Un utilisateur avec ce nom d\'utilisateur ou email existe déjà.';
    }

    // Si pas d'erreurs, procéder à l'enregistrement
    if (count($errors) === 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $passwordHash])) {
            // Connexion automatique de l'utilisateur après l'inscription
            $userId = $pdo->lastInsertId();
            $_SESSION['user_id'] = $userId;
            header('Location: dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Erreur lors de l\'enregistrement. Veuillez réessayer.';
        }
    }
}

// Inclusion du template d'en-tête
include '../templates/header.php';
?>

<div class="container">
    <h2>Inscription</h2>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="register.php" method="POST">
        Username: <input type="text" name="username" value="<?= htmlspecialchars(isset($username) ? $username : '') ?>"><br>
        Email: <input type="email" name="email" value="<?= htmlspecialchars(isset($email) ? $email : '') ?>"><br>
        Password: <input type="password" name="password"><br>
        <button type="submit">S'inscrire</button>
    </form>
</div>
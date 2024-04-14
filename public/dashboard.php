<?php
session_start();

// Inclusion des fichiers nécessaires
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Task.php';

// Vérifier si l'utilisateur est connecté
if (!Auth::check()) {
    header('Location: login.php');
    exit;
}

// Création de l'instance de connexion à la base de données
$pdo = require '../config/database.php';

// Récupération de l'ID de l'utilisateur connecté
$userId = $_SESSION['user_id'];

// Récupérer les tâches de l'utilisateur
$tasks = Task::getTasksByUserId($pdo, $userId);

// Inclusion du template d'en-tête
include '../templates/header.php';
?>

<div class="container">
    <h2>Tableau de bord</h2>
    <a href="logout.php">Déconnexion</a>
    <a href="tasks.php">Créer une nouvelle tâche</a>
    <h3>Vos Tâches</h3>
    <?php if (empty($tasks)): ?>
        <p>Aucune tâche à afficher.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <strong><?= htmlspecialchars($task['title']); ?></strong> -
                    <?= htmlspecialchars($task['description']); ?><br>
                    Statut: <?= htmlspecialchars($task['status']); ?><br>
                    <a href="tasks.php?edit=<?= $task['id']; ?>">Modifier</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
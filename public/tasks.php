<?php
session_start();

// Inclusion des fichiers nécessaires
require_once '../config/database.php';
require_once '../src/Auth.php';
require_once '../src/Task.php';

// Vérification de l'authentification de l'utilisateur
if (!Auth::check()) {
    header('Location: index.php');
    exit;
}

// Création de l'instance de connexion à la base de données
$pdo = require '../config/database.php';

// Initialisation des variables pour le formulaire
$task_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$isEditing = isset($task_id);
$title = '';
$description = '';
$status = '';
$assigned_to = '';

// Traitement des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $_SESSION['user_id']; // L'ID de l'utilisateur connecté

    if ($isEditing) {
        // Mise à jour d'une tâche existante
        if (Task::updateTask($pdo, $task_id, $title, $description, $status, $assigned_to)) {
            $message = "Tâche mise à jour avec succès.";
        } else {
            $error = "Erreur lors de la mise à jour de la tâche.";
        }
    } else {
        // Création d'une nouvelle tâche
        if (Task::createTask($pdo, $title, $description, $status, $assigned_to, $created_by)) {
            $message = "Tâche créée avec succès.";
        } else {
            $error = "Erreur lors de la création de la tâche.";
        }
    }
}

// Chargement des données de la tâche en mode édition
if ($isEditing) {
    $task = Task::getTaskById($pdo, $task_id);
    if ($task) {
        $title = $task['title'];
        $description = $task['description'];
        $status = $task['status'];
        $assigned_to = $task['assigned_to'];
    } else {
        $error = "Tâche introuvable.";
    }
}

// Inclusion du template d'en-tête
include '../templates/header.php';
?>

<div class="container">
    <h2><?= $isEditing ? 'Modifier la Tâche' : 'Créer une Nouvelle Tâche' ?></h2>
    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="<?= $isEditing ? '?edit=' . htmlspecialchars($task_id) : 'tasks.php' ?>" method="POST">
        Titre: <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required><br>
        Description: <textarea name="description" required><?= htmlspecialchars($description) ?></textarea><br>
        Statut: <select name="status">
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>En attente</option>
            <option value="in_progress" <?= $status === 'in_progress' ? 'selected' : '' ?>>En cours</option>
            <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Complétée</option>
        </select><br>
        Attribuer à: <input type="number" name="assigned_to" value="<?= htmlspecialchars($assigned_to) ?>" required><br>
        <button type="submit"><?= $isEditing ? 'Mettre à Jour' : 'Créer' ?></button>
    </form>
</div>

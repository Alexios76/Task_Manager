<?php

class Task
{
    /**
     * Crée une nouvelle tâche dans la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param string $title Le titre de la tâche.
     * @param string $description La description de la tâche.
     * @param int $createdBy L'ID de l'utilisateur qui crée la tâche.
     * @param int $assignedTo L'ID de l'utilisateur à qui la tâche est assignée.
     * @return bool Retourne true si la tâche est créée avec succès, sinon false.
     */
    public static function createTask(PDO $pdo, $title, $description, $createdBy, $assignedTo)
    {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, created_by, assigned_to) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $createdBy);
        $stmt->bindParam(4, $assignedTo);

        return $stmt->execute();
    }

    /**
     * Récupère les tâches créées par un utilisateur.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     * @return array Un tableau des tâches.
     */
    public static function getTasksByCreator(PDO $pdo, $userId)
    {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE created_by = ?");
        $stmt->bindParam(1, $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le statut d'une tâche.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $taskId L'ID de la tâche.
     * @param string $status Le nouveau statut ('pending', 'in_progress', 'completed').
     * @return bool Retourne true si la mise à jour est réussie, sinon false.
     */
    public static function updateTaskStatus(PDO $pdo, $taskId, $status)
    {
        $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $taskId);

        return $stmt->execute();
    }

    /**
     * Supprime une tâche de la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $taskId L'ID de la tâche.
     * @return bool Retourne true si la tâche est supprimée avec succès, sinon false.
     */
    public static function deleteTask(PDO $pdo, $taskId)
    {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bindParam(1, $taskId);

        return $stmt->execute();
    }

    /**
     * Récupère les tâches assignées à un utilisateur spécifique.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     * @return array Un tableau contenant les tâches assignées à l'utilisateur.
     */
    public static function getTasksByUserId(PDO $pdo, $userId)
    {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to = ?");
        $stmt->bindParam(1, $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une tâche spécifique par son ID.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $taskId L'ID de la tâche.
     * @return array|null Les données de la tâche ou null si aucune tâche n'est trouvée.
     */
    public static function getTaskById(PDO $pdo, $taskId)
    {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->bindParam(1, $taskId);
        $stmt->execute();

        // Récupère la tâche. fetch() retourne un seul enregistrement.
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task ? $task : null;
    }

    /**
     * Met à jour une tâche existante dans la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $taskId L'ID de la tâche à mettre à jour.
     * @param string $title Le nouveau titre de la tâche.
     * @param string $description La nouvelle description de la tâche.
     * @param string $status Le nouveau statut de la tâche.
     * @param int $assignedTo L'ID de l'utilisateur à qui la tâche est désormais assignée.
     * @return bool Retourne true si la mise à jour est réussie, sinon false.
     */
    public static function updateTask(PDO $pdo, $taskId, $title, $description, $status, $assignedTo)
    {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, assigned_to = ? WHERE id = ?");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $status);
        $stmt->bindParam(4, $assignedTo);
        $stmt->bindParam(5, $taskId);

        return $stmt->execute();
    }
}



<?php

class User
{
    /**
     * Ajoute un nouvel utilisateur à la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param string $username Le nom d'utilisateur.
     * @param string $email L'email de l'utilisateur.
     * @param string $password Le mot de passe (non haché).
     * @return bool Retourne true si l'utilisateur est ajouté avec succès, sinon false.
     */
    public static function register(PDO $pdo, $username, $email, $password)
    {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Préparation de la requête d'insertion
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $hashed_password);

        // Exécution de la requête
        return $stmt->execute();
    }

    /**
     * Récupère les informations d'un utilisateur par son ID.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     * @return mixed Les données de l'utilisateur ou false si l'utilisateur n'existe pas.
     */
    public static function getUserById(PDO $pdo, $userId)
    {
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->bindParam(1, $userId);
        $stmt->execute();

        // Récupération de l'utilisateur
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour les informations d'un utilisateur.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     * @param string $username Le nouveau nom d'utilisateur.
     * @param string $email Le nouvel email.
     * @return bool Retourne true si la mise à jour est réussie, sinon false.
     */
    public static function updateUser(PDO $pdo, $userId, $username, $email)
    {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(3, $userId);

        return $stmt->execute();
    }

    /**
     * Supprime un utilisateur de la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     * @return bool Retourne true si l'utilisateur est supprimé avec succès, sinon false.
     */
    public static function deleteUser(PDO $pdo, $userId)
    {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bindParam(1, $userId);

        return $stmt->execute();
    }
}

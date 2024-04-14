<?php

class Auth
{
    /**
     * Vérifie si l'utilisateur est actuellement connecté.
     * @return bool
     */
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Tente de connecter l'utilisateur avec le nom d'utilisateur ou email et mot de passe fournis.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param string $username_or_email Le nom d'utilisateur ou l'email fourni.
     * @param string $password Le mot de passe fourni.
     * @return bool Retourne true si la connexion est réussie, sinon false.
     */
    public static function attempt(PDO $pdo, $username_or_email, $password)
    {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username_or_email, $username_or_email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            self::createToken($pdo, $user['id']);  // Pass PDO object and user ID
            return true;
        }

        return false;
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public static function logout()
    {
        unset($_SESSION['user_id']);
        session_destroy();
        setcookie(session_name(), '', time() - 42000);
    }

    /**
     * Génère un JWT pour l'utilisateur et le stocke dans la base de données.
     * @param PDO $pdo L'objet PDO pour les opérations de base de données.
     * @param int $userId L'ID de l'utilisateur.
     */
    private static function createToken(PDO $pdo, $userId)
    {
        $key = 'secret_key';
        $expiryTime = time() + (86400 * 7); // Le token expire dans 7 jours
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['user_id' => $userId, 'exp' => $expiryTime]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        // Stocker le JWT dans la base de données
        $stmt = $pdo->prepare("INSERT INTO user_sessions (user_id, token, expiry_time) VALUES (?, ?, FROM_UNIXTIME(?))");
        $stmt->bindParam(1, $userId);
        $stmt->bindParam(2, $jwt);
        $stmt->bindParam(3, $expiryTime);
        $stmt->execute();

        $_SESSION['jwt'] = $jwt; // Optionnel, si vous souhaitez également le stocker dans la session
    }
}


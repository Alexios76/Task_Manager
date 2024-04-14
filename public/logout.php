<?php
session_start();

// Inclusion du gestionnaire d'authentification
require_once '../src/Auth.php';

// Appel de la méthode logout de la classe Auth pour nettoyer la session
Auth::logout();

// Redirection vers la page de connexion
header('Location: login.php');
exit;
ECE B2Gr1 ; Cour: Dev Back-End API REST | Mini-projet: Système de Gestion de Tâches avec Gestion des Accès Utilisateurs en PHP | Groupe: Gabriel BROUSSE

### Fonctionnalités Principales

1. **Inscription et Connexion d'Utilisateurs**
   - Les utilisateurs peuvent s'inscrire en fournissant un nom d'utilisateur, une adresse e-mail et un mot de passe. Les mots de passe sont hachés pour la sécurité avant d'être stockés dans la base de données.
   - Les utilisateurs peuvent se connecter à l'application en utilisant leur nom d'utilisateur ou leur e-mail et mot de passe. Un token JWT est généré lors de la connexion pour gérer les sessions de manière sécurisée.

2. **Gestion des Tâches**
   - **Création de Tâches** : Les utilisateurs peuvent créer des tâches en spécifiant un titre, une description et en assignant la tâche à un utilisateur.
   - **Modification de Tâches** : Les tâches peuvent être modifiées après leur création pour changer le titre, la description, le statut, ou l'assignation.
   - **Vue d'Ensemble des Tâches** : Les utilisateurs peuvent voir une liste de toutes les tâches qu'ils ont créées ou qui leur sont assignées, avec la capacité de filtrer par statut (en attente, en cours, terminée).

3. **Gestion des Sessions Utilisateurs**
   - Les sessions sont gérées à l'aide de JWT stockés dans la table `user_sessions` avec une date d'expiration. Cela permet de sécuriser les requêtes et de valider les sessions actives.

### Structure de l'Application

- **Base de Données**
  - `users` : Contient les informations des utilisateurs (id, username, email, password).
  - `tasks` : Stocke les détails des tâches (id, title, description, status, assigned_to, created_by).
  - `user_sessions` : Enregistre les tokens JWT avec les identifiants des utilisateurs et les dates d'expiration.

- **Fichiers PHP Principaux**
  - `index.php` : Page d'accueil et de connexion.
  - `dashboard.php` : Tableau de bord pour la gestion des tâches.
  - `register.php` et `login.php` : Traitement des inscriptions et des connexions.
  - `tasks.php` : Page pour la création et la modification des tâches.
  - `logout.php` : Permet aux utilisateurs de se déconnecter.

- **Répertoires**
  - `config/` : Contient les configurations de la base de données.
  - `src/` : Contient les classes PHP pour les opérations liées aux utilisateurs, aux tâches et à l'authentification.
  - `templates/` : Contient les fichiers de template pour les éléments de l'interface utilisateur réutilisables comme les en-têtes et les pieds de page.

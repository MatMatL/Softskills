# Application d'Aide aux Devoirs - Softskills

Application web PHP permettant aux étudiants de publier des questions sur leurs devoirs et de recevoir de l'aide de la communauté.

## Technologies

- PHP 7.4+
- SQLite
- HTML/CSS/JavaScript

## Installation

### Prérequis

- PHP 7.4 ou supérieur
- Extension PDO SQLite activée
- Serveur web (Apache/Nginx) ou serveur PHP intégré

### Configuration

1. **Initialiser la base de données**

   Exécutez le script d'initialisation depuis la ligne de commande :

   ```bash
   php config/init_db.php
   ```

   Ou via le serveur web en accédant à `http://localhost/config/init_db.php` (une seule fois)

   Ce script va :
   - Créer le dossier `database/` s'il n'existe pas
   - Créer toutes les tables nécessaires
   - Insérer les matières par défaut
   - Créer les index pour optimiser les performances

2. **Vérifier les permissions**

   Assurez-vous que le serveur web a les droits d'écriture sur le dossier `database/`.

## Structure de la base de données

### Tables

- **users** : Utilisateurs de l'application
- **matieres** : Liste des matières disponibles
- **posts** : Posts/questions des utilisateurs
- **comments** : Commentaires sur les posts
- **likes** : Likes des utilisateurs sur les posts

Voir `DOCUMENTATION.md` pour plus de détails sur le schéma de la base de données.

## Utilisation

Une fois la base de données initialisée, vous pouvez commencer à développer l'application.

## Documentation

Consultez `DOCUMENTATION.md` pour la documentation complète du projet.


# Guide d'installation

## Prérequis

- PHP 7.4 ou supérieur
- Extension PDO SQLite activée
- Serveur web (Apache/Nginx) ou serveur PHP intégré

## Installation

### 1. Initialiser la base de données

Exécutez le script d'initialisation depuis la ligne de commande :

```bash
php config/init_db.php
```

Ou via le navigateur en accédant à :
```
http://localhost/config/init_db.php
```

**Important** : Exécutez ce script une seule fois pour créer la base de données.

### 2. Vérifier les permissions

Assurez-vous que le serveur web a les droits d'écriture sur le dossier `database/`.

### 3. Configurer le serveur web

#### Option A : Serveur PHP intégré (développement)

```bash
php -S localhost:8000
```

Puis accédez à `http://localhost:8000` dans votre navigateur.

#### Option B : Apache/Nginx

Configurez votre serveur web pour pointer vers le répertoire du projet.

### 4. Tester l'application

1. Accédez à l'application dans votre navigateur
2. Créez un compte utilisateur via la page d'inscription
3. Connectez-vous avec vos identifiants
4. Créez votre premier post !

## Structure du projet

```
/
├── actions/              # Actions CRUD (create, update, delete)
├── assets/               # CSS et JavaScript
│   ├── css/
│   └── js/
├── config/               # Configuration et initialisation DB
├── includes/             # Fichiers communs (header, footer, fonctions)
├── models/               # Modèles de données (Post, Comment, Like, etc.)
├── database/             # Base de données SQLite (créée après init)
├── index.php             # Page d'accueil
├── login.php             # Page de connexion
├── register.php          # Page d'inscription
├── post.php              # Page de détail d'un post
├── create_post.php       # Création de post
├── edit_post.php         # Édition de post
├── edit_comment.php      # Édition de commentaire
├── profile.php           # Profil utilisateur
└── logout.php            # Déconnexion
```

## Première utilisation

1. **Créer un compte** : Cliquez sur "Inscription" et remplissez le formulaire
2. **Se connecter** : Utilisez vos identifiants pour vous connecter
3. **Créer un post** : Cliquez sur "Nouveau post" pour poser une question
4. **Commenter** : Répondez aux questions des autres utilisateurs
5. **Liker** : Montrez votre appréciation en likant les posts

## Dépannage

### Erreur de connexion à la base de données

- Vérifiez que le script `init_db.php` a été exécuté
- Vérifiez les permissions du dossier `database/`
- Vérifiez que l'extension PDO SQLite est activée dans PHP

### Les styles ne s'affichent pas

- Vérifiez que le chemin vers `assets/css/style.css` est correct
- Vérifiez les permissions des fichiers CSS

### Erreur 404 sur les pages

- Vérifiez la configuration de votre serveur web
- Assurez-vous que le répertoire de travail est correct

## Support

Pour toute question ou problème, consultez la documentation :
- `DOCUMENTATION.md` - Documentation complète du projet
- `CRUD_DOCUMENTATION.md` - Documentation des CRUDs


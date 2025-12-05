# Documentation du Projet - Application d'Aide aux Devoirs

## 1. Vue d'ensemble du projet

### 1.1 Description
Application web permettant aux étudiants de publier des questions sur leurs devoirs et de recevoir de l'aide de la communauté. Les utilisateurs peuvent créer des posts, commenter les posts d'autres utilisateurs, et interagir via un système de likes.

### 1.2 Objectifs
- Faciliter l'entraide entre étudiants
- Centraliser les questions et réponses sur les devoirs
- Permettre une recherche efficace des contenus
- Offrir une interface moderne et intuitive

### 1.3 Technologies utilisées
- **Backend** : PHP
- **Base de données** : SQLite
- **Frontend** : HTML, CSS, JavaScript
- **Architecture** : Application web classique (MVC recommandé)

---

## 2. Fonctionnalités principales

### 2.1 Gestion des utilisateurs

#### 2.1.1 Inscription
- Formulaire d'inscription avec :
  - Email (unique, utilisé pour la connexion)
  - Mot de passe (hashé)
  - Pseudo (unique, affiché sur le site)
- Validation des données (format email, force du mot de passe)
- Message de confirmation après inscription

#### 2.1.2 Connexion
- Formulaire de connexion avec email et mot de passe
- Gestion de session utilisateur
- Redirection vers la page d'accueil après connexion

#### 2.1.3 Déconnexion
- Bouton de déconnexion accessible depuis toutes les pages
- Destruction de la session

#### 2.1.4 Profil utilisateur
- Affichage du pseudo de l'utilisateur connecté
- Possibilité de voir les posts et commentaires d'un utilisateur

### 2.2 Gestion des posts

#### 2.2.1 Création de post
- Formulaire de création accessible uniquement aux utilisateurs connectés
- Champs requis :
  - Titre du post
  - Matière (sélection depuis une liste)
  - Contenu/question (texte)
- Date de création automatique
- Auteur associé au post

#### 2.2.2 Affichage des posts
- Liste des posts sur la page d'accueil
- Affichage de :
  - Titre
  - Auteur (pseudo)
  - Matière
  - Date de publication
  - Nombre de commentaires
  - Nombre de likes
- Tri par date (plus récent en premier) par défaut
- Possibilité de trier par nombre de likes, matière, etc.

#### 2.2.3 Consultation d'un post
- Page détaillée d'un post affichant :
  - Toutes les informations du post
  - Liste des commentaires associés
  - Formulaire d'ajout de commentaire (si connecté)
  - Bouton de like (si connecté)

#### 2.2.4 Modification/Suppression de post
- L'auteur peut modifier son propre post
- L'auteur peut supprimer son propre post
- Confirmation avant suppression

### 2.3 Gestion des commentaires

#### 2.3.1 Ajout de commentaire
- Formulaire d'ajout de commentaire sur la page d'un post
- Accessible uniquement aux utilisateurs connectés
- Champs requis :
  - Contenu du commentaire (texte)
- Date de création automatique
- Auteur associé au commentaire

#### 2.3.2 Affichage des commentaires
- Liste des commentaires sous chaque post
- Affichage de :
  - Auteur (pseudo)
  - Date de publication
  - Contenu
- Tri par date (plus ancien en premier)

#### 2.3.3 Modification/Suppression de commentaire
- L'auteur peut modifier son propre commentaire
- L'auteur peut supprimer son propre commentaire
- Confirmation avant suppression

### 2.4 Système de likes

#### 2.4.1 Like/Unlike d'un post
- Bouton de like sur chaque post (si connecté)
- Un utilisateur ne peut liker qu'une seule fois un post
- Possibilité de retirer son like (unlike)
- Affichage du nombre total de likes

### 2.5 Système de recherche

#### 2.5.1 Recherche de posts
- Barre de recherche accessible depuis toutes les pages
- Recherche par :
  - Mots-clés dans le titre et le contenu
  - Matière
  - Auteur
- Affichage des résultats de recherche
- Tri des résultats par pertinence ou date

#### 2.5.2 Filtres
- Filtrage par matière
- Filtrage par date (récent, ancien)
- Filtrage par popularité (nombre de likes)

### 2.6 Gestion des matières

#### 2.6.1 Liste des matières
- Liste prédéfinie de matières (ex: Mathématiques, Français, Histoire, Sciences, etc.)
- Possibilité d'ajouter de nouvelles matières (si nécessaire)
- Affichage du nombre de posts par matière

---

## 3. Architecture technique

### 3.1 Structure de la base de données (SQLite)

#### Table `users`
```sql
- id (INTEGER PRIMARY KEY AUTOINCREMENT)
- email (TEXT UNIQUE NOT NULL)
- password (TEXT NOT NULL) -- Hashé avec password_hash()
- pseudo (TEXT UNIQUE NOT NULL)
- created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
```

#### Table `posts`
```sql
- id (INTEGER PRIMARY KEY AUTOINCREMENT)
- user_id (INTEGER NOT NULL, FOREIGN KEY REFERENCES users(id))
- title (TEXT NOT NULL)
- content (TEXT NOT NULL)
- matiere (TEXT NOT NULL)
- created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
- updated_at (DATETIME)
```

#### Table `comments`
```sql
- id (INTEGER PRIMARY KEY AUTOINCREMENT)
- post_id (INTEGER NOT NULL, FOREIGN KEY REFERENCES posts(id) ON DELETE CASCADE)
- user_id (INTEGER NOT NULL, FOREIGN KEY REFERENCES users(id))
- content (TEXT NOT NULL)
- created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
- updated_at (DATETIME)
```

#### Table `likes`
```sql
- id (INTEGER PRIMARY KEY AUTOINCREMENT)
- post_id (INTEGER NOT NULL, FOREIGN KEY REFERENCES posts(id) ON DELETE CASCADE)
- user_id (INTEGER NOT NULL, FOREIGN KEY REFERENCES users(id))
- created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
- UNIQUE(post_id, user_id) -- Un utilisateur ne peut liker qu'une fois un post
```

#### Table `matieres` (optionnelle, pour gérer dynamiquement les matières)
```sql
- id (INTEGER PRIMARY KEY AUTOINCREMENT)
- nom (TEXT UNIQUE NOT NULL)
- created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
```

### 3.2 Structure des fichiers PHP

```
/
├── index.php                 # Page d'accueil (liste des posts)
├── login.php                 # Page de connexion
├── register.php              # Page d'inscription
├── logout.php                # Script de déconnexion
├── post.php                  # Page de détail d'un post
├── create_post.php           # Page de création de post
├── edit_post.php             # Page d'édition de post
├── delete_post.php           # Script de suppression de post
├── add_comment.php           # Script d'ajout de commentaire
├── edit_comment.php          # Page d'édition de commentaire
├── delete_comment.php        # Script de suppression de commentaire
├── like_post.php             # Script de like/unlike
├── search.php                # Page de résultats de recherche
├── profile.php               # Page de profil utilisateur
├── config/
│   ├── database.php          # Configuration de la connexion à la DB
│   └── init_db.php           # Script d'initialisation de la DB
├── includes/
│   ├── header.php            # En-tête commun
│   ├── footer.php            # Pied de page commun
│   └── functions.php         # Fonctions utilitaires
├── assets/
│   ├── css/
│   │   └── style.css         # Styles CSS
│   └── js/
│       └── main.js           # JavaScript
└── database/
    └── softskills.db         # Fichier SQLite
```

### 3.3 Sécurité

- **Hashage des mots de passe** : Utilisation de `password_hash()` et `password_verify()`
- **Protection CSRF** : Tokens CSRF pour les formulaires sensibles
- **Protection XSS** : Échappement de toutes les données utilisateur avec `htmlspecialchars()`
- **Protection SQL Injection** : Utilisation de requêtes préparées (PDO)
- **Validation des données** : Validation côté serveur de tous les formulaires
- **Gestion des sessions** : Sessions PHP sécurisées

---

## 4. Interface utilisateur

### 4.1 Design
- Design moderne et épuré
- Palette de couleurs claire et professionnelle
- Typographie lisible
- Espacement généreux
- Responsive design (adaptation mobile/tablette/desktop)

### 4.2 Pages principales

#### 4.2.1 Page d'accueil
- En-tête avec navigation (logo, recherche, liens connexion/inscription ou profil/déconnexion)
- Liste des posts avec aperçu
- Filtres par matière
- Pagination si nécessaire

#### 4.2.2 Page de connexion
- Formulaire de connexion centré
- Lien vers la page d'inscription
- Messages d'erreur clairs

#### 4.2.3 Page d'inscription
- Formulaire d'inscription centré
- Validation en temps réel (optionnelle)
- Lien vers la page de connexion

#### 4.2.4 Page de détail d'un post
- Affichage complet du post
- Liste des commentaires
- Formulaire d'ajout de commentaire
- Bouton de like
- Actions de modification/suppression (si auteur)

#### 4.2.5 Page de création/édition de post
- Formulaire avec tous les champs nécessaires
- Éditeur de texte simple ou riche (optionnel)
- Boutons de validation/annulation

### 4.3 Navigation
- Menu principal accessible depuis toutes les pages
- Breadcrumbs pour la navigation (optionnel)
- Liens de retour vers la page précédente

---

## 5. Cas d'usage

### 5.1 Utilisateur non connecté
1. Consulter la liste des posts
2. Consulter un post et ses commentaires
3. Effectuer une recherche
4. S'inscrire ou se connecter

### 5.2 Utilisateur connecté
1. Toutes les fonctionnalités de l'utilisateur non connecté
2. Créer un nouveau post
3. Commenter un post
4. Liker/unliker un post
5. Modifier/supprimer ses propres posts
6. Modifier/supprimer ses propres commentaires
7. Accéder à son profil

---

## 6. Matières prédéfinies

Liste initiale des matières :
- Mathématiques
- Français
- Histoire
- Géographie
- Sciences (Physique, Chimie, SVT)
- Langues vivantes (Anglais, Espagnol, etc.)
- Philosophie
- Économie
- Informatique
- Autres

---

## 7. Points techniques à implémenter

### 7.1 Priorité haute
- Système d'authentification complet
- CRUD des posts
- CRUD des commentaires
- Système de likes
- Recherche basique

### 7.2 Priorité moyenne
- Filtres avancés
- Pagination
- Validation côté client (JavaScript)
- Messages flash pour les actions utilisateur

### 7.3 Priorité basse (améliorations futures)
- Système de notifications
- Éditeur de texte riche
- Upload d'images dans les posts
- Système de tags
- Modération des contenus
- Système de réputation utilisateur

---

## 8. Contraintes et limitations

- Base de données SQLite (limite de taille pour la production)
- Pas de système de modération automatique
- Pas de système de notifications en temps réel
- Pas de système de messagerie privée

---

## 9. Tests à prévoir

- Tests d'inscription/connexion
- Tests de création/modification/suppression de posts
- Tests de création/modification/suppression de commentaires
- Tests de likes
- Tests de recherche
- Tests de sécurité (injection SQL, XSS)
- Tests de validation des formulaires

---

## 10. Documentation technique supplémentaire

### 10.1 Configuration PHP requise
- PHP 7.4 ou supérieur
- Extension PDO SQLite activée
- Sessions PHP activées

### 10.2 Installation
1. Cloner/déployer le projet
2. Configurer les droits d'écriture pour le dossier `database/`
3. Exécuter le script d'initialisation de la base de données
4. Configurer le serveur web (Apache/Nginx) ou utiliser le serveur PHP intégré

---

## 11. Évolutions possibles

- API REST pour une future application mobile
- Système de modération avec rôles administrateur/moderateur
- Système de badges/réputation
- Export des posts en PDF
- Intégration avec des outils d'apprentissage en ligne
- Système de favoris pour sauvegarder des posts

---

**Version du document** : 1.0  
**Date de création** : 2024  
**Auteur** : Documentation du projet Softskills


# Documentation des CRUDs

Cette documentation explique comment utiliser les modèles et actions CRUD créés pour l'application.

## Structure

### Modèles (`models/`)
Les modèles contiennent la logique métier pour interagir avec la base de données :
- `Post.php` - Gestion des posts
- `Comment.php` - Gestion des commentaires
- `Like.php` - Gestion des likes
- `Matiere.php` - Gestion des matières

### Actions (`actions/`)
Les actions gèrent les requêtes HTTP et appellent les modèles :
- `create_post.php` - Créer un post
- `update_post.php` - Modifier un post
- `delete_post.php` - Supprimer un post
- `add_comment.php` - Ajouter un commentaire
- `update_comment.php` - Modifier un commentaire
- `delete_comment.php` - Supprimer un commentaire
- `like_post.php` - Liker/unliker un post

### Includes (`includes/`)
Fichiers utilitaires :
- `auth.php` - Fonctions d'authentification
- `functions.php` - Fonctions utilitaires
- `header.php` - En-tête HTML commun
- `footer.php` - Pied de page HTML commun

## Utilisation des modèles

### Post

#### Créer un post
```php
require_once 'models/Post.php';
$postId = Post::create($userId, $title, $content, $matiere);
```

#### Récupérer un post par ID
```php
$post = Post::getById($postId);
// Retourne : ['id', 'user_id', 'title', 'content', 'matiere', 'created_at', 'updated_at', 'author_pseudo', 'comment_count', 'like_count']
```

#### Récupérer tous les posts
```php
// Tous les posts
$posts = Post::getAll($limit = 10, $offset = 0);

// Filtrer par matière
$posts = Post::getAll(10, 0, 'Mathématiques');

// Recherche
$posts = Post::getAll(10, 0, null, 'recherche');
```

#### Compter les posts
```php
$total = Post::count();
$totalByMatiere = Post::count('Mathématiques');
```

#### Mettre à jour un post
```php
$success = Post::update($postId, $title, $content, $matiere);
```

#### Supprimer un post
```php
$success = Post::delete($postId);
```

### Comment

#### Créer un commentaire
```php
require_once 'models/Comment.php';
$commentId = Comment::create($postId, $userId, $content);
```

#### Récupérer un commentaire par ID
```php
$comment = Comment::getById($commentId);
```

#### Récupérer les commentaires d'un post
```php
$comments = Comment::getByPostId($postId);
```

#### Mettre à jour un commentaire
```php
$success = Comment::update($commentId, $content);
```

#### Supprimer un commentaire
```php
$success = Comment::delete($commentId);
```

### Like

#### Ajouter un like
```php
require_once 'models/Like.php';
$success = Like::add($postId, $userId);
```

#### Retirer un like
```php
$success = Like::remove($postId, $userId);
```

#### Vérifier si un utilisateur a liké
```php
$hasLiked = Like::hasLiked($postId, $userId);
```

#### Compter les likes
```php
$count = Like::count($postId);
```

#### Toggle like/unlike
```php
$action = Like::toggle($postId, $userId); // Retourne 'added' ou 'removed'
```

### Matiere

#### Récupérer toutes les matières
```php
require_once 'models/Matiere.php';
$matieres = Matiere::getAll();
```

#### Récupérer une matière par nom
```php
$matiere = Matiere::getByName('Mathématiques');
```

#### Récupérer les matières avec le nombre de posts
```php
$matieres = Matiere::getWithPostCount();
// Retourne : [['id', 'nom', 'post_count'], ...]
```

## Utilisation des actions

### Créer un post

Formulaire HTML :
```html
<form method="POST" action="actions/create_post.php">
    <input type="text" name="title" required>
    <textarea name="content" required></textarea>
    <select name="matiere" required>
        <!-- Options des matières -->
    </select>
    <button type="submit">Créer</button>
</form>
```

### Modifier un post

Formulaire HTML :
```html
<form method="POST" action="actions/update_post.php">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <input type="text" name="title" value="<?php echo escape($post['title']); ?>" required>
    <textarea name="content" required><?php echo escape($post['content']); ?></textarea>
    <select name="matiere" required>
        <!-- Options des matières -->
    </select>
    <button type="submit">Modifier</button>
</form>
```

### Supprimer un post

Formulaire HTML :
```html
<form method="POST" action="actions/delete_post.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <button type="submit">Supprimer</button>
</form>
```

### Ajouter un commentaire

Formulaire HTML :
```html
<form method="POST" action="actions/add_comment.php">
    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
    <textarea name="content" required></textarea>
    <button type="submit">Commenter</button>
</form>
```

### Liker un post

Formulaire HTML avec AJAX (recommandé) :
```html
<form method="POST" action="actions/like_post.php" class="like-form">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <button type="submit" class="like-btn" data-post-id="<?php echo $post['id']; ?>">
        <?php echo Like::hasLiked($post['id'], $_SESSION['user_id']) ? 'Unlike' : 'Like'; ?>
        (<span class="like-count"><?php echo Like::count($post['id']); ?></span>)
    </button>
</form>

<script>
document.querySelectorAll('.like-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const response = await fetch('actions/like_post.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            const btn = form.querySelector('.like-btn');
            const countSpan = form.querySelector('.like-count');
            btn.textContent = data.has_liked ? 'Unlike' : 'Like';
            countSpan.textContent = data.like_count;
        }
    });
});
</script>
```

## Fonctions d'authentification

### Vérifier si connecté
```php
require_once 'includes/auth.php';
if (isLoggedIn()) {
    // Utilisateur connecté
}
```

### Récupérer l'utilisateur actuel
```php
$user = getCurrentUser();
// Retourne : ['id', 'email', 'pseudo'] ou false
```

### Vérifier si auteur d'un post
```php
if (isPostAuthor($postId)) {
    // Afficher bouton modifier/supprimer
}
```

### Vérifier si auteur d'un commentaire
```php
if (isCommentAuthor($commentId)) {
    // Afficher bouton modifier/supprimer
}
```

### Exiger la connexion
```php
requireLogin(); // Redirige vers login.php si non connecté
requireLogin('custom_login.php'); // Redirige vers une page personnalisée
```

## Fonctions utilitaires

### Échapper les données
```php
require_once 'includes/functions.php';
echo escape($userInput); // Protection XSS
```

### Formater une date
```php
echo formatDate($post['created_at']); // "Il y a 2 heures"
```

### Messages flash
```php
// Définir un message
setFlashMessage('Post créé avec succès !', 'success');

// Afficher le message (dans header.php ou footer.php)
echo getFlashMessage();
```

## Exemple complet : Page de détail d'un post

```php
<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'models/Like.php';

$pageTitle = 'Détail du post';

// Récupérer le post
$postId = intval($_GET['id'] ?? 0);
$post = Post::getById($postId);

if (!$post) {
    setFlashMessage('Post introuvable', 'error');
    header('Location: index.php');
    exit;
}

// Récupérer les commentaires
$comments = Comment::getByPostId($postId);

// Vérifier si l'utilisateur a liké
$hasLiked = false;
if (isLoggedIn()) {
    require_once 'models/Like.php';
    $hasLiked = Like::hasLiked($postId, $_SESSION['user_id']);
}

include 'includes/header.php';
?>

<h1><?php echo escape($post['title']); ?></h1>
<p>Par <?php echo escape($post['author_pseudo']); ?> - <?php echo formatDate($post['created_at']); ?></p>
<p>Matière : <?php echo escape($post['matiere']); ?></p>
<div><?php echo nl2br(escape($post['content'])); ?></div>

<?php if (isLoggedIn()): ?>
    <form method="POST" action="actions/like_post.php" class="like-form">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <button type="submit"><?php echo $hasLiked ? 'Unlike' : 'Like'; ?></button>
        <span><?php echo Like::count($post['id']); ?> likes</span>
    </form>
    
    <?php if (isPostAuthor($post['id'])): ?>
        <a href="edit_post.php?id=<?php echo $post['id']; ?>">Modifier</a>
        <form method="POST" action="actions/delete_post.php" style="display:inline;">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" onclick="return confirm('Supprimer ?');">Supprimer</button>
        </form>
    <?php endif; ?>
    
    <h2>Ajouter un commentaire</h2>
    <form method="POST" action="actions/add_comment.php">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <textarea name="content" required></textarea>
        <button type="submit">Commenter</button>
    </form>
<?php endif; ?>

<h2>Commentaires (<?php echo count($comments); ?>)</h2>
<?php foreach ($comments as $comment): ?>
    <div>
        <strong><?php echo escape($comment['author_pseudo']); ?></strong>
        <span><?php echo formatDate($comment['created_at']); ?></span>
        <p><?php echo nl2br(escape($comment['content'])); ?></p>
        
        <?php if (isLoggedIn() && isCommentAuthor($comment['id'])): ?>
            <a href="edit_comment.php?id=<?php echo $comment['id']; ?>">Modifier</a>
            <form method="POST" action="actions/delete_comment.php" style="display:inline;">
                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                <button type="submit" onclick="return confirm('Supprimer ?');">Supprimer</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
```

## Notes importantes

1. **Sécurité** : Toutes les actions vérifient l'authentification et les permissions
2. **Validation** : Les données sont validées côté serveur avant insertion
3. **Échappement** : Utilisez toujours `escape()` pour afficher les données utilisateur
4. **Messages flash** : Utilisés pour informer l'utilisateur des succès/erreurs
5. **Clés étrangères** : Les suppressions en cascade sont gérées automatiquement par SQLite


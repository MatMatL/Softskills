<?php
/**
 * Page d'édition d'un post
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Post.php';
require_once 'models/Matiere.php';

requireLogin();

$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = Post::getById($postId);

if (!$post) {
    setFlashMessage('Post introuvable', 'error');
    header('Location: index.php');
    exit;
}

// Vérifier que l'utilisateur est l'auteur
if (!isPostAuthor($postId)) {
    setFlashMessage('Vous n\'avez pas le droit de modifier ce post', 'error');
    header('Location: post.php?id=' . $postId);
    exit;
}

$pageTitle = 'Modifier le post';

// Récupérer les matières
$matieres = Matiere::getAll();

include 'includes/header.php';
?>

<div class="form-container">
    <h1>Modifier le post</h1>
    
    <form method="POST" action="actions/update_post.php" class="post-form">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        
        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" required maxlength="200" 
                   value="<?php echo escape($post['title']); ?>">
        </div>
        
        <div class="form-group">
            <label for="matiere">Matière *</label>
            <select id="matiere" name="matiere" required>
                <option value="">Sélectionner une matière</option>
                <?php foreach ($matieres as $matiere): ?>
                    <option value="<?php echo escape($matiere['nom']); ?>" 
                            <?php echo $post['matiere'] === $matiere['nom'] ? 'selected' : ''; ?>>
                        <?php echo escape($matiere['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="content">Contenu *</label>
            <textarea id="content" name="content" rows="10" required><?php echo escape($post['content']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>


<?php
/**
 * Page d'édition d'un commentaire
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Comment.php';

requireLogin();

$commentId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$comment = Comment::getById($commentId);

if (!$comment) {
    setFlashMessage('Commentaire introuvable', 'error');
    header('Location: index.php');
    exit;
}

// Vérifier que l'utilisateur est l'auteur
if (!isCommentAuthor($commentId)) {
    setFlashMessage('Vous n\'avez pas le droit de modifier ce commentaire', 'error');
    header('Location: post.php?id=' . $comment['post_id']);
    exit;
}

$pageTitle = 'Modifier le commentaire';

include 'includes/header.php';
?>

<div class="form-container">
    <h1>Modifier le commentaire</h1>
    
    <form method="POST" action="actions/update_comment.php" class="comment-form">
        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
        
        <div class="form-group">
            <label for="content">Contenu *</label>
            <textarea id="content" name="content" rows="6" required><?php echo escape($comment['content']); ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="post.php?id=<?php echo $comment['post_id']; ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>


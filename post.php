<?php
/**
 * Page de détail d'un post
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';
require_once 'models/Like.php';

$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = Post::getById($postId);

if (!$post) {
    setFlashMessage('Post introuvable', 'error');
    header('Location: index.php');
    exit;
}

$pageTitle = escape($post['title']);

// Récupérer les commentaires
$comments = Comment::getByPostId($postId);

// Vérifier si l'utilisateur a liké ce post
$hasLiked = false;
if (isLoggedIn()) {
    $hasLiked = Like::hasLiked($postId, $_SESSION['user_id']);
}

include 'includes/header.php';
?>

<article class="post-detail">
    <div class="post-header">
        <h1><?php echo escape($post['title']); ?></h1>
        <span class="post-matiere"><?php echo escape($post['matiere']); ?></span>
    </div>
    
    <div class="post-meta">
        <span class="post-author">Par <?php echo escape($post['author_pseudo']); ?></span>
        <span class="post-date"><?php echo formatDate($post['created_at']); ?></span>
    </div>
    
    <div class="post-content">
        <?php echo nl2br(escape($post['content'])); ?>
    </div>
    
    <div class="post-actions">
        <?php if (isLoggedIn()): ?>
            <form method="POST" action="actions/like_post.php" class="like-form">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" class="btn-like <?php echo $hasLiked ? 'liked' : ''; ?>" 
                        data-post-id="<?php echo $post['id']; ?>">
                    <?php echo $hasLiked ? '❤️' : '🤍'; ?>
                    <span class="like-count"><?php echo Like::count($post['id']); ?></span>
                </button>
            </form>
        <?php else: ?>
            <div class="like-info">
                ❤️ <?php echo Like::count($post['id']); ?> likes
            </div>
        <?php endif; ?>
        
        <?php if (isLoggedIn() && isPostAuthor($post['id'])): ?>
            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Modifier</a>
            <form method="POST" action="actions/delete_post.php" style="display:inline;" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        <?php endif; ?>
    </div>
</article>

<!-- Section commentaires -->
<div class="comments-section">
    <h2>Commentaires (<?php echo count($comments); ?>)</h2>
    
    <?php if (isLoggedIn()): ?>
        <div class="add-comment">
            <h3>Ajouter un commentaire</h3>
            <form method="POST" action="actions/add_comment.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <div class="form-group">
                    <textarea name="content" rows="4" required placeholder="Votre commentaire..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Commenter</button>
            </form>
        </div>
    <?php else: ?>
        <p class="login-prompt">
            <a href="login.php">Connectez-vous</a> pour commenter
        </p>
    <?php endif; ?>
    
    <div class="comments-list">
        <?php if (empty($comments)): ?>
            <p class="no-comments">Aucun commentaire pour le moment.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-card">
                    <div class="comment-header">
                        <strong><?php echo escape($comment['author_pseudo']); ?></strong>
                        <span class="comment-date"><?php echo formatDate($comment['created_at']); ?></span>
                    </div>
                    <div class="comment-content">
                        <?php echo nl2br(escape($comment['content'])); ?>
                    </div>
                    <?php if (isLoggedIn() && isCommentAuthor($comment['id'])): ?>
                        <div class="comment-actions">
                            <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn-link">Modifier</a>
                            <form method="POST" action="actions/delete_comment.php" style="display:inline;" 
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" class="btn-link btn-danger">Supprimer</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


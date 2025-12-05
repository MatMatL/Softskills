<?php
/**
 * Page de profil utilisateur
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Post.php';
require_once 'models/Comment.php';

requireLogin();

$pageTitle = 'Mon profil';

$user = getCurrentUser();
$userId = $user['id'];

// Récupérer les posts de l'utilisateur
$userPosts = Post::getByUserId($userId);

// Récupérer les commentaires de l'utilisateur
$userComments = Comment::getByUserId($userId);

include 'includes/header.php';
?>

<div class="profile-container">
    <div class="profile-header">
        <h1>Mon profil</h1>
        <div class="profile-info">
            <p><strong>Pseudo :</strong> <?php echo escape($user['pseudo']); ?></p>
            <p><strong>Email :</strong> <?php echo escape($user['email']); ?></p>
        </div>
    </div>
    
    <div class="profile-content">
        <div class="profile-section">
            <h2>Mes posts (<?php echo count($userPosts); ?>)</h2>
            
            <?php if (empty($userPosts)): ?>
                <p class="empty-state">Vous n'avez pas encore créé de post.</p>
                <a href="create_post.php" class="btn btn-primary">Créer mon premier post</a>
            <?php else: ?>
                <div class="posts-list">
                    <?php foreach ($userPosts as $post): ?>
                        <article class="post-card">
                            <div class="post-header">
                                <h3><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo escape($post['title']); ?></a></h3>
                                <span class="post-matiere"><?php echo escape($post['matiere']); ?></span>
                            </div>
                            <div class="post-content">
                                <?php 
                                $content = escape($post['content']);
                                $preview = strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                                echo nl2br($preview); 
                                ?>
                            </div>
                            <div class="post-footer">
                                <span class="post-date"><?php echo formatDate($post['created_at']); ?></span>
                                <div class="post-stats">
                                    <span class="stat-item">💬 <?php echo $post['comment_count']; ?></span>
                                    <span class="stat-item">❤️ <?php echo $post['like_count']; ?></span>
                                </div>
                                <div class="post-actions">
                                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn-link">Modifier</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="profile-section">
            <h2>Mes commentaires (<?php echo count($userComments); ?>)</h2>
            
            <?php if (empty($userComments)): ?>
                <p class="empty-state">Vous n'avez pas encore commenté de post.</p>
            <?php else: ?>
                <div class="comments-list">
                    <?php foreach ($userComments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <a href="post.php?id=<?php echo $comment['post_id']; ?>">
                                    <strong><?php echo escape($comment['post_title']); ?></strong>
                                </a>
                                <span class="comment-date"><?php echo formatDate($comment['created_at']); ?></span>
                            </div>
                            <div class="comment-content">
                                <?php 
                                $content = escape($comment['content']);
                                $preview = strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                                echo nl2br($preview); 
                                ?>
                            </div>
                            <div class="comment-actions">
                                <a href="post.php?id=<?php echo $comment['post_id']; ?>" class="btn-link">Voir le post</a>
                                <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn-link">Modifier</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


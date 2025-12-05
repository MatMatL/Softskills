<?php
/**
 * Action pour ajouter un commentaire
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Post.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    
    // Vérifier que le post existe
    $post = Post::getById($postId);
    if (!$post) {
        setFlashMessage('Post introuvable', 'error');
        header('Location: index.php');
        exit;
    }
    
    // Validation
    if (empty($content)) {
        setFlashMessage('Le contenu du commentaire est requis', 'error');
        header('Location: post.php?id=' . $postId);
        exit;
    }
    
    $commentId = Comment::create($postId, $_SESSION['user_id'], $content);
    
    if ($commentId) {
        setFlashMessage('Commentaire ajouté avec succès !', 'success');
    } else {
        setFlashMessage('Erreur lors de l\'ajout du commentaire', 'error');
    }
    
    header('Location: post.php?id=' . $postId);
    exit;
} else {
    header('Location: index.php');
    exit;
}


<?php
/**
 * Action pour supprimer un commentaire
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = intval($_POST['comment_id'] ?? 0);
    
    // Vérifier que le commentaire existe et que l'utilisateur en est l'auteur
    $comment = Comment::getById($commentId);
    if (!$comment) {
        setFlashMessage('Commentaire introuvable', 'error');
        header('Location: index.php');
        exit;
    }
    
    if (!isCommentAuthor($commentId)) {
        setFlashMessage('Vous n\'avez pas le droit de supprimer ce commentaire', 'error');
        header('Location: post.php?id=' . $comment['post_id']);
        exit;
    }
    
    $postId = $comment['post_id'];
    $success = Comment::delete($commentId);
    
    if ($success) {
        setFlashMessage('Commentaire supprimé avec succès !', 'success');
    } else {
        setFlashMessage('Erreur lors de la suppression du commentaire', 'error');
    }
    
    header('Location: post.php?id=' . $postId);
    exit;
} else {
    header('Location: index.php');
    exit;
}


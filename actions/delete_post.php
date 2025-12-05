<?php
/**
 * Action pour supprimer un post
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Post.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id'] ?? 0);
    
    // Vérifier que le post existe et que l'utilisateur en est l'auteur
    if (!isPostAuthor($postId)) {
        setFlashMessage('Vous n\'avez pas le droit de supprimer ce post', 'error');
        header('Location: ../index.php');
        exit;
    }
    
    $success = Post::delete($postId);
    
    if ($success) {
        setFlashMessage('Post supprimé avec succès !', 'success');
    } else {
        setFlashMessage('Erreur lors de la suppression du post', 'error');
    }
    
    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../index.php');
    exit;
}


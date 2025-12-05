<?php
/**
 * Action pour mettre à jour un commentaire
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = intval($_POST['comment_id'] ?? 0);
    $content = trim($_POST['content'] ?? '');
    
    // Vérifier que le commentaire existe et que l'utilisateur en est l'auteur
    $comment = Comment::getById($commentId);
    if (!$comment) {
        setFlashMessage('Commentaire introuvable', 'error');
        header('Location: index.php');
        exit;
    }
    
    if (!isCommentAuthor($commentId)) {
        setFlashMessage('Vous n\'avez pas le droit de modifier ce commentaire', 'error');
        header('Location: post.php?id=' . $comment['post_id']);
        exit;
    }
    
    // Validation
    if (empty($content)) {
        setFlashMessage('Le contenu du commentaire est requis', 'error');
        header('Location: post.php?id=' . $comment['post_id']);
        exit;
    }
    
    $success = Comment::update($commentId, $content);
    
    if ($success) {
        setFlashMessage('Commentaire modifié avec succès !', 'success');
    } else {
        setFlashMessage('Erreur lors de la modification du commentaire', 'error');
    }
    
    header('Location: post.php?id=' . $comment['post_id']);
    exit;
} else {
    header('Location: index.php');
    exit;
}


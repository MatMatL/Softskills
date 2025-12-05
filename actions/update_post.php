<?php
/**
 * Action pour mettre à jour un post
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Matiere.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $matiere = trim($_POST['matiere'] ?? '');
    
    // Vérifier que le post existe et que l'utilisateur en est l'auteur
    if (!isPostAuthor($postId)) {
        setFlashMessage('Vous n\'avez pas le droit de modifier ce post', 'error');
        header('Location: ../index.php');
        exit;
    }
    
    $errors = [];
    
    // Validation
    if (empty($title)) {
        $errors[] = 'Le titre est requis';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Le titre ne peut pas dépasser 200 caractères';
    }
    
    if (empty($content)) {
        $errors[] = 'Le contenu est requis';
    }
    
    if (empty($matiere)) {
        $errors[] = 'La matière est requise';
    } else {
        // Vérifier que la matière existe
        $matiereExists = Matiere::getByName($matiere);
        if (!$matiereExists) {
            $errors[] = 'Matière invalide';
        }
    }
    
    if (empty($errors)) {
        $success = Post::update($postId, $title, $content, $matiere);
        
        if ($success) {
            setFlashMessage('Post modifié avec succès !', 'success');
            header('Location: ../post.php?id=' . $postId);
            exit;
        } else {
            $errors[] = 'Erreur lors de la modification du post';
        }
    }
    
    // Si erreurs, les stocker en session pour les afficher
    if (!empty($errors)) {
        setFlashMessage(implode('<br>', $errors), 'error');
        header('Location: ../edit_post.php?id=' . $postId);
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}


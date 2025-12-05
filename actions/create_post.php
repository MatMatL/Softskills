<?php
/**
 * Action pour créer un nouveau post
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Matiere.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $matiere = trim($_POST['matiere'] ?? '');
    
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
        $postId = Post::create($_SESSION['user_id'], $title, $content, $matiere);
        
        if ($postId) {
            setFlashMessage('Post créé avec succès !', 'success');
            header('Location: post.php?id=' . $postId);
            exit;
        } else {
            $errors[] = 'Erreur lors de la création du post';
        }
    }
    
    // Si erreurs, les stocker en session pour les afficher
    if (!empty($errors)) {
        setFlashMessage(implode('<br>', $errors), 'error');
        header('Location: create_post.php');
        exit;
    }
} else {
    header('Location: create_post.php');
    exit;
}


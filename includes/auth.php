<?php
/**
 * Fonctions d'authentification et de gestion de session
 */

session_start();

require_once __DIR__ . '/../config/database.php';

/**
 * Vérifie si un utilisateur est connecté
 * @return bool True si connecté, false sinon
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Récupère les informations de l'utilisateur connecté
 * @return array|false Tableau avec les infos utilisateur ou false si non connecté
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return false;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id, email, pseudo FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Vérifie si l'utilisateur connecté est l'auteur d'un post
 * @param int $postId ID du post
 * @return bool True si l'utilisateur est l'auteur
 */
function isPostAuthor($postId) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();
    
    return $post && $post['user_id'] == $_SESSION['user_id'];
}

/**
 * Vérifie si l'utilisateur connecté est l'auteur d'un commentaire
 * @param int $commentId ID du commentaire
 * @return bool True si l'utilisateur est l'auteur
 */
function isCommentAuthor($commentId) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT user_id FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch();
    
    return $comment && $comment['user_id'] == $_SESSION['user_id'];
}

/**
 * Redirige vers une page si l'utilisateur n'est pas connecté
 * @param string $redirectUrl URL de redirection (par défaut login.php)
 */
function requireLogin($redirectUrl = 'login.php') {
    if (!isLoggedIn()) {
        header('Location: ' . $redirectUrl);
        exit;
    }
}


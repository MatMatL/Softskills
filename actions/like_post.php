<?php
/**
 * Action pour liker/unliker un post
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Post.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = intval($_POST['post_id'] ?? 0);
    
    // Vérifier que le post existe
    $post = Post::getById($postId);
    if (!$post) {
        http_response_code(404);
        echo json_encode(['error' => 'Post introuvable']);
        exit;
    }
    
    // Toggle le like
    $action = Like::toggle($postId, $_SESSION['user_id']);
    $likeCount = Like::count($postId);
    $hasLiked = Like::hasLiked($postId, $_SESSION['user_id']);
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'action' => $action,
        'like_count' => $likeCount,
        'has_liked' => $hasLiked
    ]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}


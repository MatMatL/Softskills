<?php
/**
 * Modèle pour la gestion des likes
 */

require_once __DIR__ . '/../config/database.php';

class Like {
    
    /**
     * Ajoute un like à un post
     * @param int $postId ID du post
     * @param int $userId ID de l'utilisateur
     * @return bool True si succès, false sinon
     */
    public static function add($postId, $userId) {
        $db = getDB();
        try {
            $stmt = $db->prepare("
                INSERT INTO likes (post_id, user_id) 
                VALUES (?, ?)
            ");
            return $stmt->execute([$postId, $userId]);
        } catch (PDOException $e) {
            // Si le like existe déjà (contrainte UNIQUE), retourne false
            return false;
        }
    }
    
    /**
     * Retire un like d'un post
     * @param int $postId ID du post
     * @param int $userId ID de l'utilisateur
     * @return bool True si succès, false sinon
     */
    public static function remove($postId, $userId) {
        $db = getDB();
        try {
            $stmt = $db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            return $stmt->execute([$postId, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Vérifie si un utilisateur a liké un post
     * @param int $postId ID du post
     * @param int $userId ID de l'utilisateur
     * @return bool True si l'utilisateur a liké le post
     */
    public static function hasLiked($postId, $userId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Compte le nombre de likes d'un post
     * @param int $postId ID du post
     * @return int Nombre de likes
     */
    public static function count($postId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    /**
     * Toggle un like (ajoute s'il n'existe pas, retire s'il existe)
     * @param int $postId ID du post
     * @param int $userId ID de l'utilisateur
     * @return string 'added' ou 'removed'
     */
    public static function toggle($postId, $userId) {
        if (self::hasLiked($postId, $userId)) {
            self::remove($postId, $userId);
            return 'removed';
        } else {
            self::add($postId, $userId);
            return 'added';
        }
    }
}


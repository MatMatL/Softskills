<?php
/**
 * Modèle pour la gestion des commentaires
 */

require_once __DIR__ . '/../config/database.php';

class Comment {
    
    /**
     * Crée un nouveau commentaire
     * @param int $postId ID du post
     * @param int $userId ID de l'utilisateur
     * @param string $content Contenu du commentaire
     * @return int|false ID du commentaire créé ou false en cas d'erreur
     */
    public static function create($postId, $userId, $content) {
        $db = getDB();
        try {
            $stmt = $db->prepare("
                INSERT INTO comments (post_id, user_id, content) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$postId, $userId, $content]);
            return $db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupère un commentaire par son ID
     * @param int $commentId ID du commentaire
     * @return array|false Tableau avec les données du commentaire ou false
     */
    public static function getById($commentId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.*, u.pseudo as author_pseudo
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$commentId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère tous les commentaires d'un post
     * @param int $postId ID du post
     * @return array Liste des commentaires
     */
    public static function getByPostId($postId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.*, u.pseudo as author_pseudo
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Met à jour un commentaire
     * @param int $commentId ID du commentaire
     * @param string $content Nouveau contenu
     * @return bool True si succès, false sinon
     */
    public static function update($commentId, $content) {
        $db = getDB();
        try {
            $stmt = $db->prepare("
                UPDATE comments 
                SET content = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            return $stmt->execute([$content, $commentId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Supprime un commentaire
     * @param int $commentId ID du commentaire
     * @return bool True si succès, false sinon
     */
    public static function delete($commentId) {
        $db = getDB();
        try {
            $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
            return $stmt->execute([$commentId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupère les commentaires d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des commentaires
     */
    public static function getByUserId($userId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT c.*, p.title as post_title, p.id as post_id
            FROM comments c
            LEFT JOIN posts p ON c.post_id = p.id
            WHERE c.user_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}


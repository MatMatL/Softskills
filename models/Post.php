<?php
/**
 * Modèle pour la gestion des posts
 */

require_once __DIR__ . '/../config/database.php';

class Post {
    
    /**
     * Crée un nouveau post
     * @param int $userId ID de l'utilisateur
     * @param string $title Titre du post
     * @param string $content Contenu du post
     * @param string $matiere Matière du post
     * @return int|false ID du post créé ou false en cas d'erreur
     */
    public static function create($userId, $title, $content, $matiere) {
        $db = getDB();
        try {
            $stmt = $db->prepare("
                INSERT INTO posts (user_id, title, content, matiere) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $title, $content, $matiere]);
            return $db->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupère un post par son ID
     * @param int $postId ID du post
     * @return array|false Tableau avec les données du post ou false
     */
    public static function getById($postId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.*, u.pseudo as author_pseudo, 
                   COUNT(DISTINCT c.id) as comment_count,
                   COUNT(DISTINCT l.id) as like_count
            FROM posts p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN comments c ON p.id = c.post_id
            LEFT JOIN likes l ON p.id = l.post_id
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$postId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère tous les posts avec pagination
     * @param int $limit Nombre de posts par page
     * @param int $offset Offset pour la pagination
     * @param string|null $matiere Filtre par matière (optionnel)
     * @param string|null $search Recherche par mots-clés (optionnel)
     * @return array Liste des posts
     */
    public static function getAll($limit = 10, $offset = 0, $matiere = null, $search = null) {
        $db = getDB();
        $where = [];
        $params = [];
        
        if ($matiere) {
            $where[] = "p.matiere = ?";
            $params[] = $matiere;
        }
        
        if ($search) {
            $where[] = "(p.title LIKE ? OR p.content LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $stmt = $db->prepare("
            SELECT p.*, u.pseudo as author_pseudo,
                   COUNT(DISTINCT c.id) as comment_count,
                   COUNT(DISTINCT l.id) as like_count
            FROM posts p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN comments c ON p.id = c.post_id
            LEFT JOIN likes l ON p.id = l.post_id
            $whereClause
            GROUP BY p.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $limit;
        $params[] = $offset;
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre total de posts
     * @param string|null $matiere Filtre par matière (optionnel)
     * @param string|null $search Recherche par mots-clés (optionnel)
     * @return int Nombre de posts
     */
    public static function count($matiere = null, $search = null) {
        $db = getDB();
        $where = [];
        $params = [];
        
        if ($matiere) {
            $where[] = "matiere = ?";
            $params[] = $matiere;
        }
        
        if ($search) {
            $where[] = "(title LIKE ? OR content LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM posts $whereClause");
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    /**
     * Met à jour un post
     * @param int $postId ID du post
     * @param string $title Nouveau titre
     * @param string $content Nouveau contenu
     * @param string $matiere Nouvelle matière
     * @return bool True si succès, false sinon
     */
    public static function update($postId, $title, $content, $matiere) {
        $db = getDB();
        try {
            $stmt = $db->prepare("
                UPDATE posts 
                SET title = ?, content = ?, matiere = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            return $stmt->execute([$title, $content, $matiere, $postId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Supprime un post
     * @param int $postId ID du post
     * @return bool True si succès, false sinon
     */
    public static function delete($postId) {
        $db = getDB();
        try {
            $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
            return $stmt->execute([$postId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Récupère les posts d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des posts
     */
    public static function getByUserId($userId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.*, 
                   COUNT(DISTINCT c.id) as comment_count,
                   COUNT(DISTINCT l.id) as like_count
            FROM posts p
            LEFT JOIN comments c ON p.id = c.post_id
            LEFT JOIN likes l ON p.id = l.post_id
            WHERE p.user_id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}


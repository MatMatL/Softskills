<?php
/**
 * Modèle pour la gestion des matières
 */

require_once __DIR__ . '/../config/database.php';

class Matiere {
    
    /**
     * Récupère toutes les matières
     * @return array Liste des matières
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM matieres ORDER BY nom ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère une matière par son ID
     * @param int $matiereId ID de la matière
     * @return array|false Tableau avec les données de la matière ou false
     */
    public static function getById($matiereId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM matieres WHERE id = ?");
        $stmt->execute([$matiereId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère une matière par son nom
     * @param string $nom Nom de la matière
     * @return array|false Tableau avec les données de la matière ou false
     */
    public static function getByName($nom) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM matieres WHERE nom = ?");
        $stmt->execute([$nom]);
        return $stmt->fetch();
    }
    
    /**
     * Compte le nombre de posts par matière
     * @return array Liste des matières avec leur nombre de posts
     */
    public static function getWithPostCount() {
        $db = getDB();
        $stmt = $db->query("
            SELECT m.*, COUNT(p.id) as post_count
            FROM matieres m
            LEFT JOIN posts p ON m.nom = p.matiere
            GROUP BY m.id
            ORDER BY m.nom ASC
        ");
        return $stmt->fetchAll();
    }
}


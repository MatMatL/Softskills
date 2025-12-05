<?php
/**
 * Configuration de la connexion à la base de données SQLite
 */

// Chemin vers le fichier de base de données
define('DB_PATH', __DIR__ . '/../database/softskills.db');

/**
 * Obtient une instance de connexion à la base de données
 * @return PDO Instance PDO pour interagir avec la base de données
 */
function getDB() {
    try {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // Activer les clés étrangères
        $db->exec('PRAGMA foreign_keys = ON');
        return $db;
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}

/**
 * Vérifie si la base de données existe
 * @return bool True si la base existe, false sinon
 */
function dbExists() {
    return file_exists(DB_PATH);
}


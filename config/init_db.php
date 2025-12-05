<?php
/**
 * Script d'initialisation de la base de données SQLite
 * 
 * Ce script crée toutes les tables nécessaires pour l'application
 * Exécuter ce script une seule fois pour initialiser la base de données
 */

require_once __DIR__ . '/database.php';

// Créer le dossier database s'il n'existe pas
$dbDir = dirname(DB_PATH);
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

try {
    $db = getDB();
    
    echo "Initialisation de la base de données...\n";
    
    // Table des utilisateurs
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            pseudo TEXT UNIQUE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Table 'users' créée\n";
    
    // Table des matières
    $db->exec("
        CREATE TABLE IF NOT EXISTS matieres (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT UNIQUE NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Table 'matieres' créée\n";
    
    // Table des posts
    $db->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            matiere TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Table 'posts' créée\n";
    
    // Table des commentaires
    $db->exec("
        CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            content TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    echo "✓ Table 'comments' créée\n";
    
    // Table des likes
    $db->exec("
        CREATE TABLE IF NOT EXISTS likes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(post_id, user_id)
        )
    ");
    echo "✓ Table 'likes' créée\n";
    
    // Créer des index pour améliorer les performances
    $db->exec("CREATE INDEX IF NOT EXISTS idx_posts_user_id ON posts(user_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_posts_matiere ON posts(matiere)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_posts_created_at ON posts(created_at DESC)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_comments_post_id ON comments(post_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_comments_user_id ON comments(user_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_likes_post_id ON likes(post_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_likes_user_id ON likes(user_id)");
    echo "✓ Index créés\n";
    
    // Insérer les matières par défaut
    $matieres = [
        'Mathématiques',
        'Français',
        'Histoire',
        'Géographie',
        'Sciences (Physique, Chimie, SVT)',
        'Anglais',
        'Espagnol',
        'Allemand',
        'Philosophie',
        'Économie',
        'Informatique',
        'Autres'
    ];
    
    $stmt = $db->prepare("INSERT OR IGNORE INTO matieres (nom) VALUES (?)");
    foreach ($matieres as $matiere) {
        $stmt->execute([$matiere]);
    }
    echo "✓ Matières par défaut insérées\n";
    
    echo "\n✅ Base de données initialisée avec succès !\n";
    echo "Fichier de base de données : " . DB_PATH . "\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur lors de l'initialisation : " . $e->getMessage() . "\n";
    exit(1);
}


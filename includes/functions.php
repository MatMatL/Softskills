<?php
/**
 * Fonctions utilitaires
 */

/**
 * Échappe les données pour éviter les injections XSS
 * @param string $data Donnée à échapper
 * @return string Donnée échappée
 */
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Formate une date pour l'affichage
 * @param string $date Date au format SQL
 * @return string Date formatée
 */
function formatDate($date) {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return 'Il y a ' . $diff . ' seconde' . ($diff > 1 ? 's' : '');
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return 'Il y a ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return 'Il y a ' . $hours . ' heure' . ($hours > 1 ? 's' : '');
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return 'Il y a ' . $days . ' jour' . ($days > 1 ? 's' : '');
    } else {
        return date('d/m/Y à H:i', $timestamp);
    }
}

/**
 * Valide un email
 * @param string $email Email à valider
 * @return bool True si valide
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Affiche un message flash
 * @param string $message Message à afficher
 * @param string $type Type de message (success, error, info, warning)
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Récupère et affiche le message flash
 * @return string|null HTML du message ou null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        $class = 'alert-' . $type;
        return '<div class="alert ' . escape($class) . '">' . escape($message) . '</div>';
    }
    return null;
}


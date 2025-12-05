<?php
// S'assurer que les fonctions nécessaires sont chargées
if (!function_exists('escape')) {
    require_once __DIR__ . '/functions.php';
}
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/auth.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) : 'Aide aux Devoirs'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        // Charger le thème immédiatement pour éviter le flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">
                <span class="logo-icon">📚</span>
                <span class="logo-text">Aide aux Devoirs</span>
            </a>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li>
                    <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Accueil</span>
                    </a>
                </li>
                
                <?php if (isLoggedIn()): ?>
                    <?php $currentUser = getCurrentUser(); ?>
                    <li>
                        <a href="create_post.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'create_post.php' ? 'active' : ''; ?>">
                            <span class="nav-icon">✍️</span>
                            <span class="nav-text">Nouveau post</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                            <span class="nav-icon">👤</span>
                            <span class="nav-text">Mon profil</span>
                        </a>
                    </li>
                    <li class="nav-divider"></li>
                    <li>
                        <a href="profile.php" class="nav-item nav-user">
                            <span class="nav-icon">👋</span>
                            <span class="nav-text"><?php echo escape($currentUser['pseudo']); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="nav-item nav-logout">
                            <span class="nav-icon">🚪</span>
                            <span class="nav-text">Déconnexion</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="login.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">
                            <span class="nav-icon">🔑</span>
                            <span class="nav-text">Connexion</span>
                        </a>
                    </li>
                    <li>
                        <a href="register.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : ''; ?>">
                            <span class="nav-icon">📝</span>
                            <span class="nav-text">Inscription</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- Dark Mode Toggle -->
                <li class="nav-divider"></li>
                <li>
                    <button class="nav-item nav-dark-toggle" id="darkModeToggle" aria-label="Toggle dark mode">
                        <span class="nav-icon" id="darkModeIcon">🌙</span>
                        <span class="nav-text">Mode sombre</span>
                    </button>
                </li>
            </ul>
        </nav>
    </aside>
    
    <!-- Overlay pour mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Contenu principal -->
    <div class="main-wrapper">
        <main class="main-content">
            <?php
            $flash = getFlashMessage();
            if ($flash) {
                echo $flash;
            }
            ?>


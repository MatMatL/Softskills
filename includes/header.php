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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="index.php" class="logo">Aide aux Devoirs</a>
                <div class="nav-links">
                    <a href="index.php">Accueil</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="create_post.php">Nouveau post</a>
                        <?php $currentUser = getCurrentUser(); ?>
                        <a href="profile.php"><?php echo escape($currentUser['pseudo']); ?></a>
                        <a href="logout.php">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php">Connexion</a>
                        <a href="register.php">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <main class="container">
        <?php
        $flash = getFlashMessage();
        if ($flash) {
            echo $flash;
        }
        ?>


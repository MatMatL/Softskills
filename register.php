<?php
/**
 * Page d'inscription
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'config/database.php';

$pageTitle = 'Inscription';
$errors = [];

// Si déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $pseudo = trim($_POST['pseudo'] ?? '');
    
    // Validation
    if (empty($email)) {
        $errors[] = 'L\'email est requis';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'Format d\'email invalide';
    }
    
    if (empty($pseudo)) {
        $errors[] = 'Le pseudo est requis';
    } elseif (strlen($pseudo) < 3) {
        $errors[] = 'Le pseudo doit contenir au moins 3 caractères';
    } elseif (strlen($pseudo) > 50) {
        $errors[] = 'Le pseudo ne peut pas dépasser 50 caractères';
    }
    
    if (empty($password)) {
        $errors[] = 'Le mot de passe est requis';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères';
    }
    
    if ($password !== $passwordConfirm) {
        $errors[] = 'Les mots de passe ne correspondent pas';
    }
    
    // Vérifier si l'email existe déjà
    if (empty($errors)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Cet email est déjà utilisé';
        }
    }
    
    // Vérifier si le pseudo existe déjà
    if (empty($errors)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        if ($stmt->fetch()) {
            $errors[] = 'Ce pseudo est déjà utilisé';
        }
    }
    
    if (empty($errors)) {
        $db = getDB();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (email, password, pseudo) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$email, $hashedPassword, $pseudo])) {
            setFlashMessage('Inscription réussie ! Vous pouvez maintenant vous connecter.', 'success');
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Inscription</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo escape($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo" required minlength="3" maxlength="50"
                       value="<?php echo isset($_POST['pseudo']) ? escape($_POST['pseudo']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        
        <p class="auth-link">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


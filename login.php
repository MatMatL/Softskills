<?php
/**
 * Page de connexion
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'config/database.php';

$pageTitle = 'Connexion';
$errors = [];

// Si déjà connecté, rediriger vers l'accueil
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email)) {
        $errors[] = 'L\'email est requis';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'Format d\'email invalide';
    }
    
    if (empty($password)) {
        $errors[] = 'Le mot de passe est requis';
    }
    
    if (empty($errors)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, email, password, pseudo FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_pseudo'] = $user['pseudo'];
            setFlashMessage('Connexion réussie ! Bienvenue ' . escape($user['pseudo']), 'success');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Email ou mot de passe incorrect';
        }
    }
}

include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1>Connexion</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo escape($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        
        <p class="auth-link">
            Pas encore de compte ? <a href="register.php">S'inscrire</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


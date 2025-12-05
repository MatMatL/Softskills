<?php
/**
 * Page de création d'un post
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Matiere.php';

requireLogin();

$pageTitle = 'Créer un nouveau post';

// Récupérer les matières
$matieres = Matiere::getAll();

include 'includes/header.php';
?>

<div class="form-container">
    <h1>Créer un nouveau post</h1>
    
    <form method="POST" action="actions/create_post.php" class="post-form">
        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" required maxlength="200" 
                   value="<?php echo isset($_POST['title']) ? escape($_POST['title']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="matiere">Matière *</label>
            <select id="matiere" name="matiere" required>
                <option value="">Sélectionner une matière</option>
                <?php foreach ($matieres as $matiere): ?>
                    <option value="<?php echo escape($matiere['nom']); ?>" 
                            <?php echo (isset($_POST['matiere']) && $_POST['matiere'] === $matiere['nom']) ? 'selected' : ''; ?>>
                        <?php echo escape($matiere['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="content">Contenu *</label>
            <textarea id="content" name="content" rows="10" required 
                      placeholder="Décrivez votre question ou votre besoin d'aide..."><?php echo isset($_POST['content']) ? escape($_POST['content']) : ''; ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Publier</button>
            <a href="index.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>


<?php
/**
 * Page d'accueil - Liste des posts
 */

require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Post.php';
require_once 'models/Matiere.php';

$pageTitle = 'Accueil';

// Paramètres de pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Filtres
$matiereFilter = $_GET['matiere'] ?? null;
$search = $_GET['search'] ?? null;

// Récupérer les posts
$posts = Post::getAll($limit, $offset, $matiereFilter, $search);
$totalPosts = Post::count($matiereFilter, $search);
$totalPages = ceil($totalPosts / $limit);

// Récupérer les matières pour le filtre
$matieres = Matiere::getWithPostCount();

include 'includes/header.php';
?>

<div class="page-header">
    <h1>Questions et Réponses</h1>
    <?php if (isLoggedIn()): ?>
        <a href="create_post.php" class="btn btn-primary">Nouveau post</a>
    <?php endif; ?>
</div>

<!-- Formulaire de recherche et filtres -->
<div class="filters-section">
    <form method="GET" action="index.php" class="search-form">
        <div class="search-group">
            <input type="text" name="search" placeholder="Rechercher..." 
                   value="<?php echo isset($_GET['search']) ? escape($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-secondary">Rechercher</button>
        </div>
    </form>
    
    <div class="matieres-filter">
        <strong>Filtrer par matière :</strong>
        <a href="index.php" class="filter-tag <?php echo !$matiereFilter ? 'active' : ''; ?>">Toutes</a>
        <?php foreach ($matieres as $matiere): ?>
            <a href="index.php?matiere=<?php echo urlencode($matiere['nom']); ?>" 
               class="filter-tag <?php echo $matiereFilter === $matiere['nom'] ? 'active' : ''; ?>">
                <?php echo escape($matiere['nom']); ?> (<?php echo $matiere['post_count']; ?>)
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Liste des posts -->
<?php if (empty($posts)): ?>
    <div class="empty-state">
        <p>Aucun post trouvé.</p>
        <?php if (isLoggedIn()): ?>
            <a href="create_post.php" class="btn btn-primary">Créer le premier post</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="posts-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <div class="post-header">
                    <h2><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo escape($post['title']); ?></a></h2>
                    <span class="post-matiere"><?php echo escape($post['matiere']); ?></span>
                </div>
                
                <div class="post-content">
                    <?php 
                    $content = escape($post['content']);
                    $preview = strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
                    echo nl2br($preview); 
                    ?>
                </div>
                
                <div class="post-footer">
                    <div class="post-meta">
                        <span class="post-author">Par <?php echo escape($post['author_pseudo']); ?></span>
                        <span class="post-date"><?php echo formatDate($post['created_at']); ?></span>
                    </div>
                    <div class="post-stats">
                        <span class="stat-item">💬 <?php echo $post['comment_count']; ?></span>
                        <span class="stat-item">❤️ <?php echo $post['like_count']; ?></span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo $matiereFilter ? '&matiere=' . urlencode($matiereFilter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                   class="btn btn-secondary">Précédent</a>
            <?php endif; ?>
            
            <span class="page-info">Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo $matiereFilter ? '&matiere=' . urlencode($matiereFilter) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                   class="btn btn-secondary">Suivant</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>


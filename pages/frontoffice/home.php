<?php
require_once __DIR__ . '/../../inc/connexion.php';
require_once __DIR__ . '/../../inc/repository/ArticleRepository.php';
require_once __DIR__ . '/../../inc/services/ArticleService.php';
require_once __DIR__ . '/../../inc/fonction/Rewriter.php';

use inc\repository\ArticleRepository;
use inc\services\ArticleService;

// Instancier la connexion et les services
$pdo = getConnexion();
$articleRepository = new ArticleRepository($pdo);
$articleService = new ArticleService($articleRepository);

// Récupérer les catégories pour map
$categories_base = $articleService->getAllCategories();
$categories = [];
if ($categories_base) {
    foreach ($categories_base as $cat) {
        $id = $cat['id_categorie'] ?? $cat['id_categorie'] ?? (isset($cat['Id_Categorie']) ? $cat['Id_Categorie'] : null);
        if ($id) {
            $categories[$id] = $cat['rubrique'];
        }
    }
}

$articles = [];
$filter_search = isset($_GET['search']) ? trim($_GET['search']) : null;
$filter_cat = isset($_GET['cat']) ? trim($_GET['cat']) : null;

// Récupérer les articles selon la recherche s'il y en a une
if (!empty($filter_search)) {
    $articles_base = $articleService->searchArticles($filter_search);
} else {
    $articles_base = $articleService->getTitreArticles();
}

foreach ($articles_base as $art) {
    $id = $art['id_article'] ?? $art['Id_Article'] ?? null;
    if ($id) {
        $full_article = $articleService->getArticleById($id);
        if ($full_article) {
            // Ajouter l'extrait du contenu
            $full_article['extrait'] = $art['contenu'] ?? '';
            
            // Catégorie
            $cat_id = $full_article['id_categorie'] ?? $full_article['Id_Categorie'] ?? null;
            $rubrique = $cat_id && isset($categories[$cat_id]) ? $categories[$cat_id] : 'Actualité';
            $full_article['rubrique'] = $rubrique;
            
            // Si une catégorie est filtrée, on l'applique ici :
            if (!empty($filter_cat) && strtolower($rubrique) !== strtolower($filter_cat)) {
                continue;
            }
            
            $articles[] = $full_article;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="NewsFeed - Retrouvez l'actualité en temps réel, les dernières informations, et les articles à la une.">
    <title>NewsFeed - L'actualité en temps réel</title>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style1.min.css">
    <!-- Pré-chargement des images principales si possible ou images CDN -->
</head>
<body>

    <div class="container">
        <?php require_once __DIR__ . '/../layout.php'; ?>

        <!-- Flux Principal -->
        <main class="feed">
            <div class="feed-header">
                <div class="feed-tabs">
                    <div class="tab active">Pour vous</div>
                </div>
            </div>

            <!-- Boucle PHP sur les articles dynamiques -->
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): 
                    $datePub = $article['date_publication'] ?? 'now';
                    $idArt = $article['id_article'] ?? $article['Id_Article'] ?? null;
                    
                    $auteurs_text = !empty($article['auteur']) ? trim($article['auteur']) : 'Auteur inconnu';
                    $initials = substr($auteurs_text, 0, 2);
                    $identifiant = strtolower(str_replace(' ', '', $auteurs_text));
                    if (empty($identifiant)) {
                        $identifiant = 'auteur';
                    }
                ?>
                    <a href="/article/<?= htmlspecialchars($idArt) ?>-<?= htmlspecialchars(creerSlug($article['titre'])) ?>" class="article">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($initials) ?>&background=random&color=fff" alt="Avatar de l'auteur" class="article-avatar" loading="lazy" width="48" height="48">
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="author-name">
                                    <?= htmlspecialchars($auteurs_text) ?>
                                </span>
                                <i class="fa-solid fa-circle-check" style="color: var(--primary-blue); font-size: 14px;"></i>
                                <span class="author-handle">@<?= htmlspecialchars($identifiant) ?></span>
                                <span class="post-time">· <?= htmlspecialchars((new DateTime($datePub))->format('d/m/Y H:i')) ?></span>
                            </div>
                            <div class="rubrique-tag"><?= htmlspecialchars($article['rubrique']) ?></div>
                            <h2 class="article-title"><?= htmlspecialchars(html_entity_decode(strip_tags($article['titre']), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?></h2>
                            <p class="article-snippet"><?= htmlspecialchars(html_entity_decode($article['extrait'], ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?> <span class="read-more">Voir plus</span></p>
                            
                            <?php if (!empty($article['photos'])): ?>
                                <img src="/uploads/<?= htmlspecialchars($article['photos'][0]['chemin']) ?>" alt="<?= htmlspecialchars($article['photos'][0]['alt'] ?? 'Image article'); ?>" class="article-image" loading="lazy">
                            <?php else: ?>
                                <!-- Fallback image if no photo attached -->
                                <div class="article-image" style="display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; color: #666; font-style: italic;">
                                    <?= htmlspecialchars(html_entity_decode(strip_tags($article['titre']), ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="padding: 20px; text-align: center; color: var(--text-muted);">
                    Aucun article disponible pour le moment.
                </div>
            <?php endif; ?>

        </main>

        <!-- Sidebar Droite -->
        <aside class="sidebar-right">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <form method="GET" action="/pages/frontoffice/home.php" style="flex: 1;">
                    <?php if (!empty($filter_cat)): ?>
                        <input type="hidden" name="cat" value="<?= htmlspecialchars($filter_cat) ?>">
                    <?php endif; ?>
                    <input aria-label="Recherche" type="text" name="search" placeholder="Rechercher dans l'actualité..." value="<?= htmlspecialchars($filter_search ?? '') ?>">
                </form>
            </div>

            <div class="trending-box">
                <h2 class="trending-title">Catégories</h2>
                
                <?php if (!empty($categories_base)): ?>
                    <?php foreach ($categories_base as $cat): ?>
                        <?php 
                            $cat_url = '/pages/frontoffice/home.php?cat=' . urlencode($cat['rubrique']);
                            if (!empty($filter_search)) {
                                $cat_url .= '&search=' . urlencode($filter_search);
                            }
                        ?>
                        <a href="<?= htmlspecialchars($cat_url) ?>" class="trend-item" style="text-decoration: none; color: inherit; display: block;">
                            <div class="trend-category">
                                <span>Catégorie</span>
                                <i class="fa-solid fa-ellipsis"></i>
                            </div>
                            <div class="trend-name"><?= htmlspecialchars($cat['rubrique']) ?></div>
                            <div class="trend-stats">À découvrir</div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 0 16px 16px; color: var(--text-muted);">Aucune catégorie.</div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</body>
</html>

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

// Récupérer les articles
if(isset($_GET['search']))
    {
        $criteria = trim($_GET['search']);
        $articles_base = $articleService->searchArticles($criteria);

        foreach ($articles_base as $art) {
            $id = $art['id_article'] ?? $art['Id_Article'] ?? null;
            if ($id) {
                $full_article = $articleService->getArticleById($id);
                if ($full_article) {
                    // Ajouter l'extrait du contenu venant de searchArticles
                    $full_article['extrait'] = $art['contenu'] ?? '';
                    
                    // Catégorie
                    $cat_id = $full_article['id_categorie'] ?? $full_article['Id_Categorie'] ?? null;
                    $full_article['rubrique'] = $cat_id && isset($categories[$cat_id]) ? $categories[$cat_id] : 'Actualité';
                    
                    $articles[] = $full_article;
                }
            }
        }
    }
else
    {
        $articles_base = $articleService->getTitreArticles();

        foreach ($articles_base as $art) {
            $id = $art['id_article'] ?? $art['Id_Article'] ?? null;
            if ($id) {
                $full_article = $articleService->getArticleById($id);
                if ($full_article) {
                    // Ajouter l'extrait du contenu venant de getTitreArticles
                    $full_article['extrait'] = $art['contenu'] ?? '';
                    
                    // Catégorie
                    $cat_id = $full_article['id_categorie'] ?? $full_article['Id_Categorie'] ?? null;
                    $full_article['rubrique'] = $cat_id && isset($categories[$cat_id]) ? $categories[$cat_id] : 'Actualité';
                    
                    $articles[] = $full_article;
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsFeed - L'actualité en temps réel</title>
    <!-- Polices et Icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style1.css">
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
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($initials) ?>&background=random&color=fff" alt="Avatar" class="article-avatar">
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
                                <img src="<?= htmlspecialchars($article['photos'][0]['chemin']) ?>" alt="<?= htmlspecialchars($article['photos'][0]['alt'] ?? 'Image') ?>" class="article-image">
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
                <i class="fa-solid fa-magnifying-glass"></i>
                <form method="GET" action="/pages/frontoffice/home.php" style="flex: 1;">
                    <input type="text" name="search" placeholder="Rechercher dans l'actualité..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </form>
            </div>

            <div class="trending-box">
                <h2 class="trending-title">Catégories</h2>
                
                <?php if (!empty($categories_base)): ?>
                    <?php foreach ($categories_base as $cat): ?>
                        <div class="trend-item">
                            <div class="trend-category">
                                <span>Catégorie</span>
                                <i class="fa-solid fa-ellipsis"></i>
                            </div>
                            <div class="trend-name"><?= htmlspecialchars($cat['rubrique']) ?></div>
                            <div class="trend-stats">À découvrir</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="padding: 0 16px 16px; color: var(--text-muted);">Aucune catégorie.</div>
                <?php endif; ?>
            </div>
            
            <div style="font-size: 13px; color: var(--text-muted); padding: 16px; display: flex; flex-wrap: wrap; gap: 10px;">
                <a href="#" style="color: inherit; text-decoration: none;">Conditions d'utilisation</a>
                <a href="#" style="color: inherit; text-decoration: none;">Politique de confidentialité</a>
                <a href="#" style="color: inherit; text-decoration: none;">Mentions légales</a>
                <span>© 2024 NewsFeed Corp.</span>
            </div>
        </aside>
    </div>

    <!-- Script JavaScript interactif -->
    <script>
        // Système d'onglets (Pour vous / Abonnements)
        function switchTab(element) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');
        }

        // Système de J'aime et de Sauvegarde
        function toggleAction(button, actionClass) {
            const icon = button.querySelector('i');
            const countSpan = button.querySelector('.count');
            
            button.classList.toggle(actionClass);
            
            if (actionClass === 'liked') {
                if (button.classList.contains('liked')) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    if(countSpan) {
                        let count = parseFloat(countSpan.innerText);
                        if(Number.isInteger(count)) countSpan.innerText = count + 1;
                    }
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    if(countSpan) {
                        let count = parseFloat(countSpan.innerText);
                        if(Number.isInteger(count)) countSpan.innerText = count - 1;
                    }
                }
            } else if (actionClass === 'bookmarked') {
                if (button.classList.contains('bookmarked')) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                }
            }
        }
    </script>
</body>
</html>

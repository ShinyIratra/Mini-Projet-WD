<?php
require_once __DIR__ . '/../../inc/connexion.php';
require_once __DIR__ . '/../../inc/repository/ArticleRepository.php';
require_once __DIR__ . '/../../inc/services/ArticleService.php';

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

// Récupérer les articles
$articles_base = $articleService->getTitreArticles();
$articles = [];

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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsFeed - L'actualité en temps réel</title>
    <!-- Polices et Icônes -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-main: #0f1419;
            --text-muted: #536471;
            --border-color: #eff3f4;
            --accent-color: #000000;
            --primary-blue: #1d9bf0;
            --hover-bg: rgba(15, 20, 25, 0.05);
            --icon-hover-blue: rgba(29, 155, 240, 0.1);
            --icon-hover-red: rgba(249, 24, 128, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            justify-content: center;
        }

        /* --- LAYOUT PRINCIPAL --- */
        .container {
            display: flex;
            width: 100%;
            max-width: 1260px;
            min-height: 100vh;
        }

        /* --- SIDEBAR GAUCHE (Navigation) --- */
        .sidebar-left {
            width: 275px;
            padding: 0 12px;
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            padding: 12px;
            margin-top: 5px;
            margin-bottom: 10px;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 32px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 12px;
            font-size: 20px;
            color: var(--text-main);
            text-decoration: none;
            border-radius: 30px;
            transition: background 0.2s;
            width: fit-content;
        }

        .nav-item:hover {
            background-color: var(--hover-bg);
        }

        .nav-item.active {
            font-weight: 700;
        }

        .subscribe-btn {
            background-color: var(--primary-blue);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 16px;
            font-size: 17px;
            font-weight: 700;
            margin-top: 20px;
            cursor: pointer;
            width: 90%;
            transition: background 0.2s;
        }

        .subscribe-btn:hover {
            background-color: #1a8cd8;
        }

        /* --- COLONNE CENTRALE (Le Flux) --- */
        .feed {
            width: 600px;
            border-right: 1px solid var(--border-color);
            min-height: 100vh;
        }

        .feed-header {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            z-index: 10;
        }

        .feed-tabs {
            display: flex;
            height: 53px;
        }

        .tab {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .tab:hover {
            background-color: var(--hover-bg);
        }

        .tab.active {
            color: var(--text-main);
            font-weight: 700;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            width: 60px;
            height: 4px;
            background-color: var(--primary-blue);
            border-radius: 4px;
        }

        /* Articles d'actualité */
        .article {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .article:hover {
            background-color: var(--hover-bg);
        }

        .article-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .article-content {
            flex: 1;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .author-name {
            font-weight: 700;
        }

        .author-handle, .post-time {
            color: var(--text-muted);
        }

        .rubrique-tag {
            font-size: 12px;
            color: var(--primary-blue);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: inline-block;
        }

        .article-title {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 8px;
        }

        .article-snippet {
            font-size: 15px;
            line-height: 1.5;
            color: var(--text-main);
            margin-bottom: 12px;
        }

        .read-more {
            color: var(--primary-blue);
            text-decoration: none;
        }
        
        .read-more:hover {
            text-decoration: underline;
        }

        .article-image {
            width: 100%;
            height: 300px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            margin-bottom: 12px;
            object-fit: cover;
            background-color: var(--hover-bg);
            color: var(--text-muted);
            font-style: italic;
        }

        .article-actions {
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            max-width: 425px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: color 0.2s;
        }

        .action-icon-wrapper {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 0.2s;
        }

        .action-btn:hover.comment { color: var(--primary-blue); }
        .action-btn:hover.comment .action-icon-wrapper { background-color: var(--icon-hover-blue); }
        .action-btn:hover.retweet { color: #00ba7c; }
        .action-btn:hover.retweet .action-icon-wrapper { background-color: rgba(0, 186, 124, 0.1); }
        .action-btn:hover.like { color: #f91880; }
        .action-btn:hover.like .action-icon-wrapper { background-color: var(--icon-hover-red); }
        .liked i { color: #f91880; font-weight: 900; }
        .bookmarked i { color: var(--primary-blue); font-weight: 900; }

        /* --- SIDEBAR DROITE (Tendances) --- */
        .sidebar-right {
            width: 350px;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .search-bar {
            background-color: var(--border-color);
            border-radius: 30px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 15px;
            width: 100%;
        }

        .trending-box {
            background-color: #f7f9f9;
            border-radius: 16px;
            padding: 16px 0;
        }

        .trending-title {
            font-size: 20px;
            font-weight: 800;
            padding: 0 16px 16px 16px;
        }

        .trend-item {
            padding: 12px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .trend-item:hover {
            background-color: var(--hover-bg);
        }

        .trend-category {
            font-size: 13px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
        }

        .trend-name {
            font-size: 15px;
            font-weight: 700;
            margin: 2px 0;
        }

        .trend-stats {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 1200px) {
            .sidebar-right { display: none; }
        }

        @media (max-width: 700px) {
            .sidebar-left { width: 80px; align-items: center; }
            .nav-item span { display: none; }
            .logo span { display: none; }
            .subscribe-btn { display: none; }
            .feed { width: 100%; border-right: none; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Sidebar Gauche -->
        <aside class="sidebar-left">
            <a href="#" class="logo">
                <i class="fa-solid fa-newspaper"></i>
                <span>NewsFeed</span>
            </a>
            
            <nav class="nav-links">
                <a href="#" class="nav-item active">
                    <i class="fa-solid fa-house"></i>
                    <span>À la une</span>
                </a>
                <!-- Lien pointant vers le Backoffice -->
                <a href="/pages/backoffice/articles.php" class="nav-item">
                    <i class="fa-solid fa-gear"></i>
                    <span>Backoffice</span>
                </a>
            </nav>
        </aside>

        <!-- Flux Principal -->
        <main class="feed">
            <div class="feed-header">
                <div class="feed-tabs">
                    <div class="tab active" onclick="switchTab(this)">Pour vous</div>
                    <div class="tab" onclick="switchTab(this)">Abonnements</div>
                </div>
            </div>

            <!-- Boucle PHP sur les articles dynamiques -->
            <?php if (!empty($articles)): ?>
                <?php foreach ($articles as $article): 
                    $datePub = $article['date_publication'] ?? 'now';
                    $idArt = $article['id_article'] ?? $article['Id_Article'] ?? null;
                    
                    $auteurs_text = 'Auteur inconnu';
                    $initials = 'A';
                    $identifiant = 'auteur';
                    if (!empty($article['auteurs'])) {
                        $noms = array_map(function($a) { return $a['prenom'] . ' ' . $a['nom']; }, $article['auteurs']);
                        $auteurs_text = implode(', ', $noms);
                        $prenom = $article['auteurs'][0]['prenom'] ?? '';
                        $nom = $article['auteurs'][0]['nom'] ?? '';
                        $initials = substr($prenom, 0, 1) . substr($nom, 0, 1);
                        $identifiant = $article['auteurs'][0]['identifiant'] ?? 'auteur';
                    }
                ?>
                    <a href="detail_article.php?id=<?= htmlspecialchars($idArt) ?>" class="article">
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
                <input type="text" placeholder="Rechercher dans l'actualité...">
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

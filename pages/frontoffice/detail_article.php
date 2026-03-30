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

$id_article = $_GET['id'] ?? null;
if (!$id_article) {
    die("ID d'article manquant");
}

$article = $articleService->getArticleById($id_article);

if (!$article) {
    die("Point d'accès non trouvé ou article inexistant.");
}

// Récupérer les catégories pour trouver le nom de la rubrique
$categories_base = $articleService->getAllCategories();
$rubrique = 'Actualité';
$cat_id = $article['id_categorie'] ?? $article['Id_Categorie'] ?? null;

if ($categories_base && $cat_id) {
    foreach ($categories_base as $cat) {
        $id = $cat['id_categorie'] ?? (isset($cat['Id_Categorie']) ? $cat['Id_Categorie'] : null);
        if ($id == $cat_id) {
            $rubrique = $cat['rubrique'];
            break;
        }
    }
}

// Formater la date sans IntlDateFormatter si non disponible
$date_pub = new DateTime($article['date_publication']);
// Format simple "jour mois année à H:i"
$mois_fr = [
    1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin',
    7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
];
$jour = $date_pub->format('j');
$mois = $mois_fr[(int)$date_pub->format('n')];
$annee = $date_pub->format('Y');
$heure = $date_pub->format('H\hi');

$date_formatee = "$jour $mois $annee à $heure";

// 5 Derniers articles
$latest_articles = array_slice($articleService->getTitreArticles(), 0, 5);

// Auteurs
$liste_auteurs = !empty($article['auteur']) ? trim($article['auteur']) : 'La Rédaction';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(strip_tags($article['titre'])) ?> - NewsFeed</title>
    
    <!-- Polices : Inter pour l'UI, Lora pour la lecture -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../../assets/css/style1.css">
</head>
<body>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightbox-img" src="" alt="Zoom">
        <div class="lightbox-caption" id="lightbox-caption"></div>
    </div>

    <div id="progress-bar"></div>

    <div class="container">
        <?php require_once __DIR__ . '/../layout.php'; ?>

        <!-- Colonne Centrale : Article -->
        <main class="article-container">
            <div class="top-header">
                <a href="javascript:history.back()" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
                <h2>Article</h2>
            </div>

            <article class="detail-content">
                <span class="rubrique"><?= htmlspecialchars($rubrique) ?></span>
                
                <!-- TITRE (Injecté depuis SQL : champ "titre") -->
                <div class="article-title-container">
                    <?= $article['titre'] ?>
                </div>

                <div class="author-box">
                    <div class="author-info">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode(substr($liste_auteurs, 0, 2)) ?>&background=000&color=fff" alt="Desk" class="author-avatar">
                        <div class="author-details">
                            <div class="name">Par <?= htmlspecialchars($liste_auteurs) ?></div>
                            <div class="meta">Publié le <?= $date_formatee ?></div>
                        </div>
                    </div>
                    <div class="share-actions">
                        <button class="share-btn"><i class="fa-solid fa-link"></i></button>
                        <button class="share-btn"><i class="fa-brands fa-x-twitter"></i></button>
                    </div>
                </div>

                <!-- CONTENU (Injecté depuis SQL : champ "contenu" généré par TinyMCE) -->
                <div class="tiny-mce-content">
                    <?= $article['contenu'] ?>
                </div>
            </article>
        </main>

        <!-- Sidebar Droite -->
        <aside class="sidebar-right">
            <h3 class="widget-title">Dernière minute</h3>
            <?php foreach ($latest_articles as $lat_art): 
                $cat_name_lat = 'Actualité';
                $lat_cat_id = $lat_art['id_categorie'] ?? $lat_art['Id_Categorie'] ?? null;
                if ($categories_base && $lat_cat_id) {
                    foreach ($categories_base as $cat) {
                        $c_id = $cat['id_categorie'] ?? (isset($cat['Id_Categorie']) ? $cat['Id_Categorie'] : null);
                        if ($c_id == $lat_cat_id) {
                            $cat_name_lat = $cat['rubrique'];
                            break;
                        }
                    }
                }
                $lat_id = $lat_art['id_article'] ?? $lat_art['Id_Article'] ?? '#';
            ?>
            <a href="detail_article.php?id=<?= $lat_id ?>" class="related-article">
                <img src="https://placehold.co/100x100/1d9bf0/ffffff?text=News" alt="News" class="related-img">
                <div class="related-content">
                    <span class="related-rubrique"><?= htmlspecialchars($cat_name_lat) ?></span>
                    <h4 class="related-title"><?= htmlspecialchars(strip_tags($lat_art['titre'])) ?></h4>
                </div>
            </a>
            <?php endforeach; ?>
        </aside>
    </div>

    <!-- Scripts -->
    <script>
        // Progress Bar
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            document.getElementById("progress-bar").style.width = scrolled + "%";
        });

        // Ajouter la lettrine au premier VRAI paragraphe de texte
        document.addEventListener("DOMContentLoaded", () => {
            const paragraphs = document.querySelectorAll('.tiny-mce-content p');
            for (let p of paragraphs) {
                // S'il y a du texte brut (au-delà des simples espaces) et n'est pas juste un conteneur d'image/tableau
                if (p.textContent.trim().length > 0 && !p.querySelector('img, table, iframe')) {
                    p.classList.add('has-dropcap');
                    break;
                }
            }
        });

        // --- AUTOMATISATION LIGHTBOX POUR TINYMCE ---
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxCaption = document.getElementById('lightbox-caption');

        // On cible toutes les images qui se trouvent dans le contenu généré par TinyMCE
        document.querySelectorAll('.tiny-mce-content img').forEach(img => {
            img.addEventListener('click', () => {
                lightboxImg.src = img.src;
                lightboxCaption.innerText = img.alt || "Image de l'article";
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        lightbox.addEventListener('click', function(e) {
            if (e.target !== lightboxImg) closeLightbox();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape" && lightbox.classList.contains('active')) closeLightbox();
        });
    </script>
</body>
</html>

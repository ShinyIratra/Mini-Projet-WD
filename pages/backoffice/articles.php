<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../../inc/connexion.php';
require_once __DIR__ . '/../../inc/repository/ArticleRepository.php';
require_once __DIR__ . '/../../inc/services/ArticleService.php';

$db = getConnexion();
$articleRepository = new \inc\repository\ArticleRepository($db);
$articleService = new \inc\services\ArticleService($articleRepository);

// Suppression d'article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_article'])) {
    $articleService->deleteArticle($_POST['delete_article']);
    header('Location: articles.php?success=1');
    exit;
}

$articles = $articleService->getTitreArticles();
$categories = $articleService->getAllCategories();

$categorie_map = [];
foreach ($categories as $cat) {
    $categorie_map[$cat['id_categorie']] = $cat['rubrique'];
}

// Récupérer les photos pour chaque article
foreach ($articles as &$article) {
    $article['photos'] = $articleRepository->getPhotosByArticle($article['id_article']);
}
unset($article);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des articles</title>
    <link rel="stylesheet" href="../../assets/css/styleBO.css">
</head>
<body>
    <div class="container">
        <header class="header-bo">
            <div>
                <h1>Articles</h1>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                </div>
                <a href="ajouter_article.php" class="btn-add">+ Nouvel article</a>
                <a href="logout.php" class="btn-logout">Déconnexion</a>
            </div>
        </header>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                 Opération réussie
                <button onclick="this.parentElement.style.display='none';">×</button>
            </div>
            <script>
                // Efface le paramètre 'success' de l'URL pour ne pas réafficher le message au rafraîchissement
                if (window.history.replaceState) {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('success');
                    window.history.replaceState(null, '', url.href);
                }
            </script>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <h2>Aucun article</h2>
                <p>Créez un article pour commencer</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr>
                            <td>
                                <?php if (!empty($article['photos'])): ?>
                                    <img src="/uploads/<?= htmlspecialchars($article['photos'][0]['chemin']); ?>" alt="<?= htmlspecialchars($article['photos'][0]['alt']); ?>" class="article-photo">
                                <?php else: ?>
                                    <span style="color: #999;">Pas de photo</span>
                                <?php endif; ?>
                            </td>
                            <td class="article-title"><?= htmlspecialchars(strip_tags($article['titre'])); ?></td>
                            <td><span class="category-badge"><?= htmlspecialchars($categorie_map[$article['id_categorie']] ?? 'Inconnue'); ?></span></td>
                            <td class="date-small"><?= date('d/m/Y', strtotime($article['date_publication'])); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="modifier_article.php?id=<?= $article['id_article']; ?>" class="btn-edit">Modifier</a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="delete_article" value="<?= $article['id_article']; ?>">
                                        <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

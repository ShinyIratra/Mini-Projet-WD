<h1>Gestion des Articles</h1>

<a href="/backoffice/article/new" style="padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">+ Ajouter un Article</a>

<div style="margin-top: 20px;">
    <?php foreach ($articles as $article): ?>
        <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <h3><?= htmlspecialchars($article['titre']) ?></h3>
            <p><strong>ID:</strong> <?= htmlspecialchars($article['id_article']) ?></p>
            <p><strong>Catégorie:</strong> <?= htmlspecialchars($article['id_categorie']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($article['date_publication']) ?></p>

            <?php if (!empty($article['photos'])): ?>
                <div style="margin-top: 10px;">
                    <strong>Photos:</strong>
                    <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px;">
                        <?php foreach ($article['photos'] as $photo): ?>
                            <div style="text-align: center; flex: 0 1 auto;">
                                <img src="<?= htmlspecialchars($photo['chemin']) ?>" alt="<?= htmlspecialchars($photo['alt']) ?>" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                                <p style="font-size: 13px; margin: 8px 0 0 0; max-width: 200px; word-wrap: break-word;"><em><?= htmlspecialchars($photo['alt']) ?></em></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <p><em style="color: #999;">Aucune photo</em></p>
            <?php endif; ?>

            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee;">
                <a href="/backoffice/modif_article?id_article=<?= $article['id_article'] ?>" style="padding: 8px 15px; background-color: #008CBA; color: white; text-decoration: none; border-radius: 3px;">Modifier</a>
                <a href="/backoffice/traitement-supprimer-article?id_article=<?= $article['id_article'] ?>" onclick="return confirm('Êtes-vous sûr?');" style="padding: 8px 15px; background-color: #f44336; color: white; text-decoration: none; border-radius: 3px; margin-left: 10px;">Supprimer</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (isset($_GET['error'])): ?>
    <p style="color: red; margin-top: 20px;">Erreur: <?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

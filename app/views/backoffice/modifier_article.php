<h1>Modifier l'Article</h1>

<form method="POST" action="/backoffice/traitement-modif-article" enctype="multipart/form-data">

    <input type="hidden" name="id_article" value="<?= $article['id_article'] ?>">

    <div style="margin-bottom: 15px;">
        <label for="titre">Titre:</label><br>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($article['titre']) ?>" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="contenu">Contenu:</label><br>
        <textarea id="contenu" name="contenu" required style="width: 100%; height: 250px; padding: 8px;"><?= htmlspecialchars($article['contenu']) ?></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="id_categorie">Catégorie:</label><br>
        <select id="id_categorie" name="id_categorie" required style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionnez --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= ($cat['id_categorie'] == $article['id_categorie']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['rubrique']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="id_auteur">Auteur:</label><br>
        <select id="id_auteur" name="id_auteur" style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionnez un auteur --</option>
            <?php foreach ($utilisateurs as $user): ?>
                <option value="<?= $user['id_utilisateur'] ?>" <?= ($article['auteurs'][0] && $user['id_utilisateur'] == $article['auteurs'][0]['id_utilisateur']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label><strong>Photos Existantes:</strong></label><br>
        <?php if (!empty($article['photos'])): ?>
            <div style="border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                <?php foreach ($article['photos'] as $photo): ?>
                    <div style="margin-bottom: 10px;">
                        <label>
                            <input type="checkbox" name="photos_a_supprimer[<?= $photo['id_article_photo'] ?>]" value="1">
                            Supprimer: <?= htmlspecialchars($photo['chemin']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucune photo</p>
        <?php endif; ?>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="photos">Ajouter Photos:</label><br>
        <input type="file" id="photos" name="photos[]" multiple accept="image/*" style="width: 100%; padding: 8px;">
        <div id="photos_descriptions" style="margin-top: 10px;"></div>
    </div>

    <div>
        <button type="submit" style="padding: 10px 20px; background: #008CBA; color: white; border: none; cursor: pointer;">Enregistrer</button>
        <a href="/backoffice/liste_articles" style="padding: 10px 20px; background: #f44336; color: white; text-decoration: none; margin-left: 10px;">Annuler</a>
    </div>
</form>

<script>
    // Gérer l'affichage des descriptions de photos
    document.getElementById('photos').addEventListener('change', function(e) {
        const container = document.getElementById('photos_descriptions');
        container.innerHTML = '';

        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const div = document.createElement('div');
            div.style.marginBottom = '10px';
            div.style.padding = '10px';
            div.style.border = '1px solid #ddd';
            div.style.borderRadius = '4px';
            div.innerHTML = `
                <label><strong>${file.name}</strong></label><br>
                <input type="text" name="photos_alt[]" placeholder="Description pour le alt de la photo (ex: 'Un homme à la conférence')..."
                       style="width: 100%; padding: 8px; margin-top: 5px;" required>
            `;
            container.appendChild(div);
        }
    });
</script>

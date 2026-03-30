<h1>Ajouter un Article</h1>

<form method="POST" action="/backoffice/traitement-ajout-article" enctype="multipart/form-data">

    <div style="margin-bottom: 15px;">
        <label for="titre">Titre:</label><br>
        <input type="text" id="titre" name="titre" required style="width: 100%; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="contenu">Contenu:</label><br>
        <textarea id="contenu" name="contenu" required style="width: 100%; height: 250px; padding: 8px;"></textarea>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="id_categorie">Catégorie:</label><br>
        <select id="id_categorie" name="id_categorie" required style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionnez --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['rubrique']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="id_auteur">Auteur:</label><br>
        <select id="id_auteur" name="id_auteur" style="width: 100%; padding: 8px;">
            <option value="">-- Sélectionnez un auteur --</option>
            <?php foreach ($utilisateurs as $user): ?>
                <option value="<?= $user['id_utilisateur'] ?>"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 15px;">
        <label><strong>Photos (jusqu'à 5 images):</strong></label><br>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                <label for="photo<?= $i ?>">Photo <?= $i ?>:</label><br>
                <input type="file" id="photo<?= $i ?>" name="photos[]" accept="image/*" style="width: 100%; padding: 8px; margin-bottom: 8px;">
                <label for="alt<?= $i ?>">Description (alt) pour photo <?= $i ?>:</label><br>
                <input type="text" id="alt<?= $i ?>" name="photos_alt[]" placeholder="Ex: 'Un homme à la conférence'" style="width: 100%; padding: 8px;">
            </div>
        <?php endfor; ?>
    </div>

    <div>
        <button type="submit" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 4px;">Créer</button>
        <a href="/backoffice/liste_articles" style="padding: 10px 20px; background: #f44336; color: white; text-decoration: none; margin-left: 10px; border-radius: 4px; display: inline-block;">Annuler</a>
    </div>
</form>
